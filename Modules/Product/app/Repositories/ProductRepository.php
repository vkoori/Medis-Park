<?php

namespace Modules\Product\Repositories;

use Modules\Order\Enums\OrderStatusEnum;
use Modules\Product\Models\Product;
use App\Utils\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * @extends BaseRepository<Product>
 */
class ProductRepository extends BaseRepository
{
    public function __construct(private Product $product) {}

    protected function getModel(): Product
    {
        return $this->product;
    }

    /** @return LengthAwarePaginator<Product> */
    public function paginateCustomer(int $userId, ?array $conditions = null, array $relations = [], ?int $perPage = null): LengthAwarePaginator
    {
        $query = $this->getModel()
            ->query()
            ->withCount(relations: [
                'orders as purchased' => function ($order) use ($userId) {
                    $order
                        ->where('user_id', $userId)
                        ->whereIn('status', OrderStatusEnum::completeStatuses())
                        ->whereNull('used_at');
                }
            ]);

        return $this->fetchData(conditions: $conditions, relations: $relations, query: $query)->paginate(perPage: $perPage);
    }

    protected function fetchData(?array $conditions, array $relations, ?Builder $query = null): Builder
    {
        $q = $query ?: $this->getModel()->query();

        if (isset($conditions['title'])) {
            $q->whereLike('title', "%{$conditions['title']}%");
            unset($conditions['title']);
        }

        return parent::fetchData(conditions: $conditions, relations: $relations, query: $q);
    }
}
