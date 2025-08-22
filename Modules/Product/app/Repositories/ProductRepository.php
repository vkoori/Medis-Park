<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\Product;
use App\Utils\Repository\BaseRepository;

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
}
