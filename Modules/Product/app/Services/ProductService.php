<?php

namespace Modules\Product\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Product\Dto\ProductDefineDto;

class ProductService
{
    use ClassResolver;

    public function getPostPrice(): int
    {
        $postPrice = $this->getPostPriceRepository()->lastOrFail();

        return $postPrice->coin_value;
    }

    public function saveProductWithPrice(ProductDefineDto $dto, int $userId): Product
    {
        return DB::transaction(function () use ($dto, $userId): Product {
            $media = null;
            if (
                $dto->hasField(propertyName: 'disk')
                && $dto->hasField(propertyName: 'media')
            ) {
                $media = $this->getMediaService()->upload(
                    file: $dto->media,
                    disk: $dto->disk
                );
            }

            $product = $this->getProductRepository()->create(attributes: [
                'title' => $dto->title,
                'description' => $dto->description,
                'media_id' => $media?->id,
                'updated_by' => $userId,
            ]);

            $price = $this->getProductPriceRepository()->create(attributes: [
                'product_id' => $product->id,
                'coin_value' => $dto->coinValue,
            ]);

            $product->setRelations([
                'media' => $media,
                'lastPrice' => $price,
            ]);

            return $product;
        });
    }
}
