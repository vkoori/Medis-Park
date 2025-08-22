<?php

namespace Modules\Product\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Product\Models\ProductAvailable;

/**
 * @extends BaseRepository<ProductAvailable>
 */
class ProductAvailableRepository extends BaseRepository
{
    public function __construct(private ProductAvailable $model) {}

    protected function getModel(): ProductAvailable
    {
        return $this->model;
    }
}
