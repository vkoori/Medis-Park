<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $purchased = is_int($this->purchased) ? ['purchased' => $this->purchased > 0] : [];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'media_id' => $this->media_id,
            ...$purchased,
            'media' => $this->whenLoaded(
                relationship: 'media',
                value: fn() => MediaResource::make($this->media)
            ),
            'last_price' => $this->whenLoaded(
                relationship: 'lastPrice',
                value: fn() => $this->lastPrice->coin_value
            ),
            'prices' => $this->whenLoaded(
                relationship: 'prices',
                value: fn() => ProductPriceResource::collection($this->prices)
            )
        ];
    }
}
