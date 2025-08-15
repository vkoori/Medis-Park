<?php

namespace Modules\Post\Http\Resources;

use Illuminate\Http\Request;
use Modules\User\Http\Resources\UserResource;
use Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostAdminResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'media_id' => $this->media_id,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->toIso8601String(),
            'month' => $this->month,
            'user_id' => $this->updated_by,
            'updated_at' => $this->updated_at->toIso8601String(),
            'media' => $this->whenLoaded(
                relationship: 'media',
                value: fn() => MediaResource::make($this->media)
            ),
            'updated_by' => $this->whenLoaded(
                relationship: 'updatedBy',
                value: fn() => UserResource::make($this->updatedBy)
            ),
        ];
    }
}
