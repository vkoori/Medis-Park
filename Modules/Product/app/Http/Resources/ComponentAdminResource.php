<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentAdminResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'media_id' => $this->media_id,
            'media' => $this->whenLoaded(
                relationship: 'media',
                value: fn() => MediaResource::make($this->media)
            ),
            'lastPrice' => $this->whenLoaded(
                relationship: 'lastPrice',
                value: fn() => $this->lastPrice->coin_value
            ),
            'prices' => $this->whenLoaded(
                relationship: 'prices',
                value: fn() => ComponentPriceResource::collection($this->prices)
            )
        ];
    }
}
