<?php

namespace Modules\Product\Services;

use App\Traits\ClassResolver;

class PostPriceService
{
    use ClassResolver;

    public function getLastPostPrice(): int
    {
        $postPrice = $this->getPostPriceRepository()->lastOrFail();

        return $postPrice->coin_value;
    }

    public function getAllPostPrices()
    {
        return $this->getPostPriceRepository()->get();
    }

    public function newPrice(int $coinValue)
    {
        return $this->getPostPriceRepository()->create(attributes: [
            'coin_value' => $coinValue
        ]);
    }
}
