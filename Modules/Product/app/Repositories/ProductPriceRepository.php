<?php

namespace Modules\Product\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Product\Models\ProductPrice;

/**
 * @extends BaseRepository<ProductPrice>
 */
class ProductPriceRepository extends BaseRepository
{
    public function __construct(private ProductPrice $price) {}

    protected function getModel(): ProductPrice
    {
        return $this->price;
    }
}
