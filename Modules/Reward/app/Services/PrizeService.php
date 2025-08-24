<?php

namespace Modules\Reward\Services;

use Morilog\Jalali\Jalalian;
use App\Traits\ClassResolver;
use Modules\Reward\Models\Prize;
use Illuminate\Support\Facades\DB;
use Modules\Reward\Enums\PrizeTypeEnum;
use Modules\Reward\Exceptions\PrizeExceptions;

class PrizeService
{
    use ClassResolver;

    public function addProduct(int $jYear, int $jMonth, int $productId): Prize
    {
        $this->getProductService()->show(productId: $productId, relations: []);

        $month = (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format('Y-m');

        $prize = $this->getPrizeRepository()->first(conditions: [
            'type' => PrizeTypeEnum::PRODUCT,
            'prize_reference_id' => $productId,
            'month' => $month,
        ]);
        if ($prize) {
            throw PrizeExceptions::alreadyConverted();
        }

        return $this->getPrizeRepository()->create(attributes: [
            'type' => PrizeTypeEnum::PRODUCT,
            'prize_reference_id' => $productId,
            'month' => $month,
            'ordering' => $this->nextPrizePriority(jMonth: $month),
        ]);
    }

    public function addCoin(int $jYear, int $jMonth, int $coinAmount): Prize
    {
        $month = (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format('Y-m');

        return DB::transaction(function () use ($month, $coinAmount) {
            $coinPrize = $this->getPrizeCoinRepository()->create(attributes: [
                'amount' => $coinAmount
            ]);

            return $this->getPrizeRepository()->create(attributes: [
                'type' => PrizeTypeEnum::COIN,
                'prize_reference_id' => $coinPrize->id,
                'month' => $month,
                'ordering' => $this->nextPrizePriority(jMonth: $month),
            ]);
        });
    }

    public function paginate()
    {
        return $this->getPrizeRepository()->paginate(
            relations: ['prizeable']
        );
    }

    private function nextPrizePriority(string $jMonth): int
    {
        return $this->getPrizeRepository()->count(conditions: [
            'month' => $jMonth,
        ]) + 1;
    }
}
