<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\MonthlyItem;
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
