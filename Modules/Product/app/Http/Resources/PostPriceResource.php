<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostPriceResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'coin_value' => $this->coin_value,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
