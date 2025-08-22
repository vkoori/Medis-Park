<?php

namespace Modules\Product\Services;

use App\Exceptions\NotImplementedException;
use Illuminate\Support\Facades\DB;
use Modules\Product\Exceptions\MonthlyItemExceptions;
use Morilog\Jalali\Jalalian;
use App\Traits\ClassResolver;
use Modules\Product\Dto\MonthlyCoinDto;
use Modules\Product\Models\CoinAvailable;
use Modules\Product\Dto\MonthlyComponentDto;
use Modules\Product\Models\ProductAvailable;

class MonthlyItemService
{
    use ClassResolver;

    public function addComponent(MonthlyComponentDto $dto): ProductAvailable
    {
        return $this->getProductAvailableRepository()->create(attributes: [
            'month' => $dto->getMonth(),
            'product_id' => $dto->componentId,
            'ordering' => $this->getNextPriority(jMonth: $dto->getMonth())
        ]);
    }

    public function addCoin(MonthlyCoinDto $dto): CoinAvailable
    {
        return $this->getCoinAvailableRepository()->create(attributes: [
            'month' => $dto->getMonth(),
            'amount' => $dto->amount,
            'ordering' => $this->getNextPriority(jMonth: $dto->getMonth())
        ]);
    }

    public function getMonthlyItems(int $jYear, int $jMonth)
    {
        $month = (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format(format: 'Y-m');

        return $this->getMonthlyItemRepository()->getMonthly(
            jMonth: $month,
            relations: ['product.lastPrice']
        );
    }

    /**
     * increase priority of C
     *
     * A(1) B(2) C(3) C(4) -> A(1) C(2) B(3) C(4)
     */
    public function increasePriority(int $itemId, string $itemType): void
    {
        $item = $this->findMonthlyItem(itemId: $itemId, itemType: $itemType);
        if ($item->ordering == 1) {
            throw MonthlyItemExceptions::canNotIncreaseHighestPriority();
        }

        $this->swapPriorities(item: $item, targetOrdering: $item->ordering - 1);
    }

    /**
     * decrease priority of B
     *
     * A(1) B(2) C(3) C(4) -> A(1) C(2) B(3) C(4)
     */
    public function decreasePriority(int $itemId, string $itemType)
    {
        $item = $this->findMonthlyItem(itemId: $itemId, itemType: $itemType);
        if ($item->ordering == ($this->getNextPriority(jMonth: $item->month) - 1)) {
            throw MonthlyItemExceptions::canNotDecreaseLowestPriority();
        }

        $this->swapPriorities(item: $item, targetOrdering: $item->ordering + 1);
    }

    private function getNextPriority(string $jMonth): int
    {
        $countOfComponents = $this->getProductAvailableRepository()->count(
            conditions: ['month' => $jMonth]
        );
        $countOfCoins = $this->getCoinAvailableRepository()->count(
            conditions: ['month' => $jMonth]
        );

        return $countOfComponents + $countOfCoins + 1;
    }

    private function findMonthlyItem(int $itemId, string $itemType): CoinAvailable|ProductAvailable
    {
        return match ($itemType) {
            'coin' => $this->getCoinAvailableRepository()->findByIdOrFail(modelId: $itemId),
            'component' => $this->getProductAvailableRepository()->findByIdOrFail(modelId: $itemId),
            default => throw new NotImplementedException()
        };
    }

    private function swapPriorities(object $item, int $targetOrdering): void
    {
        DB::transaction(function () use ($item, $targetOrdering) {
            $mustBeSwitch = $this->getCoinAvailableRepository()->first(conditions: [
                'month' => $item->month,
                'ordering' => $targetOrdering
            ]);

            if (!$mustBeSwitch) {
                $mustBeSwitch = $this->getProductAvailableRepository()->firstOrFail(conditions: [
                    'month' => $item->month,
                    'ordering' => $targetOrdering
                ]);
            }

            $switchRepository = match (get_class($mustBeSwitch)) {
                CoinAvailable::class => $this->getCoinAvailableRepository(),
                ProductAvailable::class => $this->getProductAvailableRepository(),
                default => throw new NotImplementedException()
            };

            $switchRepository->batchUpdate(
                conditions: ['id' => $mustBeSwitch->id],
                values: ['ordering' => $item->ordering]
            );

            match (get_class($item)) {
                CoinAvailable::class => $this->getCoinAvailableRepository()->batchUpdate(
                    conditions: ['id' => $item->id],
                    values: ['ordering' => $targetOrdering]
                ),
                ProductAvailable::class => $this->getProductAvailableRepository()->batchUpdate(
                    conditions: ['id' => $item->id],
                    values: ['ordering' => $targetOrdering]
                ),
                default => throw new NotImplementedException()
            };
        });
    }
}
