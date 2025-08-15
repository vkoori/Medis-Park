<?php

namespace Modules\Post\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserPostVisitResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'type' => $this->type,
            'first_visited_at' => $this->first_visited_at->toIso8601String(),
        ];
    }
}
