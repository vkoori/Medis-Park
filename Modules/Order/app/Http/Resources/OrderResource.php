<?php

namespace Modules\Order\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class OrderResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
            'post_id' => $this->post_id,
            'status' => $this->status,
            'coin_value' => $this->coin_value,
            'used_at' => $this->used_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'user' => $this->whenLoaded('user', function () {
                return [
                    'mobile' => $this->user->mobile->formatNational(),
                    'info' => $this->user->relationLoaded('info')
                        ? [
                            'national_code' => $this->user->info?->national_code,
                            'first_name' => $this->user->info?->first_name,
                            'last_name' => $this->user->info?->last_name,
                        ]
                        : []
                ];
            }),
            'product' => $this->whenLoaded('product', fn() => $this->product?->title),
            'post' => $this->whenLoaded('post', fn() => $this->post?->title)
        ];
    }
}
