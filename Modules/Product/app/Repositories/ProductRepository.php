<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;
use App\Utils\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Builder;

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

    protected function fetchData(?array $conditions, array $relations, ?Builder $query = null): Builder
    {
        $q = $this->getModel()->query();

        if (isset($conditions['title'])) {
            $q->whereLike('title', "%{$conditions['title']}%");
            unset($conditions['title']);
        }

        return parent::fetchData(conditions: $conditions, relations: $relations, query: $q);
    }
}
