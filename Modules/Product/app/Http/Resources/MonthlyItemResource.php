<?php

namespace Modules\Product\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonthlyItemResource extends JsonResource
{
    public function toArray(Request $request)
    {
        $isAdmin = in_array('jwt.scope:admin', $request->route()->gatherMiddleware());;
        $purchase = is_int($this->orders_count) ? ['purchase' => $this->orders_count > 0] : [];

        return [
            'item_id' => $this->item_id,
            'type' => $this->type,
            'coin_award' => $this->coin_award,
            'ordering' => $this->ordering,
            'month' => $this->month,
            ...$purchase,
            'product' => $this->whenLoaded(
                'product',
                function () use ($isAdmin) {
                    if ($isAdmin || $this->orders_count) {
                        return ComponentResource::make($this->product);
                    } else {
                        return [
                            'id' => $this->product->id,
                            'title' => $this->product->title,
                            'last_price' => $this->product->lastPrice->coin_value,
                        ];
                    }
                },
            )
        ];
    }
}
