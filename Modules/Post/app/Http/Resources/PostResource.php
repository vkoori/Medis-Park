<?php

namespace Modules\Post\Http\Resources;

use Illuminate\Http\Request;
use Modules\User\Http\Resources\UserResource;
use Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'media_id' => $this->banner,
            'title' => $this->title,
            'content' => $this->content,
            'available_at' => $this->available_at->toIso8601String(),
            'expired_at' => $this->expired_at->toIso8601String(),
            'user_id' => $this->updated_by,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
            'banner' => $this->whenLoaded(
                relationship: 'media',
                value: fn() => MediaResource::make($this->media)
            ),
            'updated_by' => $this->whenLoaded(
                relationship: 'updatedBy',
                value: fn() => UserResource::make($this->updatedBy)
            )
        ];
    }
}
