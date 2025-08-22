<?php

namespace Modules\Product\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Product\Models\Product;
use Modules\Product\Dto\ComponentSaveDto;
use Modules\Product\Dto\ComponentFilterDto;
use Modules\Product\Exceptions\ProductExceptions;

class ComponentService
{
    use ClassResolver;

    public function saveProductWithPrice(ComponentSaveDto $dto, int $userId): Product
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

    public function paginate(ComponentFilterDto $dto)
    {
        return $this->getProductRepository()->paginate(
            relations: ['lastPrice'],
            conditions: $dto->getProvidedDataSnakeCase()
        );
    }

    public function show(int $productId): Product
    {
        $product = $this->getProductRepository()->findById(
            modelId: $productId,
            relations: ['media', 'prices']
        );

        if (!$product) {
            throw ProductExceptions::notFound();
        }

        return $product;
    }

    public function newPrice(int $productId, int $coinValue)
    {
        return $this->getProductPriceRepository()->create(attributes: [
            'product_id' => $productId,
            'coin_value' => $coinValue,
        ]);
    }
}
