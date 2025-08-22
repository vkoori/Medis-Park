<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ComponentCustomerResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            // 'media' => $this->whenLoaded(
            //     relationship: 'media',
            //     value: fn() => MediaResource::make($this->media)
            // ),
            'last_price' => $this->whenLoaded(
                relationship: 'lastPrice',
                value: fn() => $this->lastPrice->coin_value
            ),
        ];
    }
}
