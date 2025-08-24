<?php

namespace Modules\Reward\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Product\Http\Resources\ProductResource;

class PrizeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'month'    => $this->month,
            'ordering' => $this->ordering,
            'created_at' => $this->created_at?->toIso8601String(),
            'prizeable' => $this->whenLoaded('prizeable', function () {
                return match ($this->type->value) {
                    'coin'    => new PrizeCoinResource($this->prizeable),
                    'product' => new ProductResource($this->prizeable),
                    default   => null,
                };
            }),
        ];
    }
}
