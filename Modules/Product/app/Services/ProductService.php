<?php

namespace Modules\Product\Services;

use App\Traits\ClassResolver;

class ProductService
{
    use ClassResolver;

    public function getPostPrice(): int
    {
        $postPrice = $this->getPostPriceRepository()->lastOrFail();

        return $postPrice->coin_value;
    }
}
