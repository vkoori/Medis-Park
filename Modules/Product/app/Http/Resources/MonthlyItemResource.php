<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyItemResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'item_id' => $this->item_id,
            'type' => $this->type,
            'coin_award' => $this->coin_award,
            'ordering' => $this->ordering,
            'month' => $this->month,
            'product' => $this->whenLoaded(
                'product',
                fn() => ComponentCustomerResource::make($this->product)
            )
        ];
    }
}
