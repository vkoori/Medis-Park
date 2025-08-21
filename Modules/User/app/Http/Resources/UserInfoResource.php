<?php

namespace Modules\User\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

class UserInfoResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var array $mobile */
        $mobile = $this->whenLoaded(
            'user',
            ['mobile' => (string) $this->user->mobile],
            []
        );
        return [
            'national_code' => $this->national_code,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'address' => $this->address,
            ...$mobile,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
