<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\MonthlyItem;
use Modules\Order\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Modules\Product\Models\CoinAvailable;
use Modules\Product\Models\ProductAvailable;

class MonthlyItemRepository
{
    public function getMonthly(string $jMonth, array $relations = [])
    {
        return $this->buildMonthlyQuery(jMonth: $jMonth)
            ->orderBy('ordering')
            ->with($relations)
            ->get();
    }

    public function getMonthlyOfCustomer(string $jMonth, int $userId, array $relations = [])
    {
        $completeStatuses = OrderStatusEnum::completeStatuses();
        $statusValues = array_map(fn($status) => $status->value, $completeStatuses);

        $placeholders = implode(',', array_fill(0, count($statusValues), '?'));

        return $this->buildMonthlyQuery(jMonth: $jMonth)
            ->orderBy('ordering')
            ->with($relations)
            ->selectRaw(
                expression: "monthly_items.*, (
                    SELECT COUNT(*) FROM orders
                    WHERE user_id = ?
                    AND status IN ($placeholders)
                    AND (
                        (monthly_items.type = 'component' AND orders.product_id = monthly_items.item_id)
                        OR
                        (monthly_items.type = 'coin' AND orders.coin_id = monthly_items.item_id)
                    )
                ) as orders_count",
                bindings: array_merge([$userId], $statusValues)
            )
            ->get();
    }

    protected function buildMonthlyQuery(string $jMonth): Builder
    {
        $coinQuery = CoinAvailable::query()
            ->selectRaw("
                id as item_id,
                null as product_id,
                'coin' as type,
                amount as coin_award,
                ordering,
                month
            ")
            ->where('month', $jMonth);

        $productQuery = ProductAvailable::query()
            ->selectRaw("
                id as item_id,
                product_id,
                'component' as type,
                null as coin_award,
                ordering,
                month
            ")
            ->where('month', $jMonth);

        return MonthlyItem::fromSub($coinQuery->unionAll($productQuery), 'monthly_items');
    }
}
