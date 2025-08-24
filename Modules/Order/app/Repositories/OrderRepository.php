<?php

namespace Modules\Order\Repositories;

use Modules\Order\Models\Order;
use App\Utils\Repository\BaseRepository;
use Modules\Order\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends BaseRepository<Order>
 */
class OrderRepository extends BaseRepository
{
    public function __construct(private Order $order) {}

    protected function getModel(): Order
    {
        return $this->order;
    }

    public function hasUnusedProduct(int $userId, int $productId): bool
    {
        return $this->getModel()
            ->query()
            ->where('user_id', $userId)
            ->where('product_id', $productId)
            ->whereIn('status', OrderStatusEnum::completeStatuses())
            ->whereNull('used_at')
            ->count() > 0;
    }

    public function usedProduct(int $orderId)
    {
        return $this->getModel()
            ->query()
            ->where('id', $orderId)
            ->whereNotNull('product_id')
            ->update([
                'used_at' => now()
            ]);
    }

    protected function fetchData(?array $conditions, array $relations, ?Builder $query = null): Builder
    {
        $query ??= $this->getModel()->query();

        if (!empty($conditions['mobile'])) {
            $query->whereHas('user', function ($user) use ($conditions) {
                $user->where('mobile', $conditions['mobile']);
            });
            unset($conditions['mobile']);
        }
        if (array_key_exists('unused', $conditions)) {
            if ($conditions['unused']) {
                $query->whereNull('used_at');
            } else {
                $query->whereNotNull('used_at');
            }
            unset($conditions['unused']);
        }

        return parent::fetchData(conditions: $conditions, relations: $relations, query: $query);
    }
}
