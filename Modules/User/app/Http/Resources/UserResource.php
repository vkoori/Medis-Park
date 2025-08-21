<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'mobile' => (string) $this->mobile,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
            // 'info' => $this->whenLoaded('info', fn() => UserInfoResource::make($this->info)),
            // 'addresses' => $this->whenLoaded('addresses', fn() => UserAddressResource::collection($this->addresses))
        ];
    }
}
