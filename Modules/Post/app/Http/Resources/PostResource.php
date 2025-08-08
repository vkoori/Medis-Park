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
        $isAdmin = in_array('jwt.scope:admin', $request->route()->middleware());

        $resource = [
            'id' => $this->id,
            'media_id' => $this->banner,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at->toIso8601String(),
            'banner' => $this->whenLoaded(
                relationship: 'media',
                value: fn() => MediaResource::make($this->media)
            ),
            'seen' => $this->whenLoaded(
                relationship: 'seen',
                value: fn() => UserPostVisitResource::collection($this->seen)
            ),
        ];

        if ($isAdmin) {
            $resource += [
                'available_at' => $this->available_at->toIso8601String(),
                'expired_at' => $this->expired_at->toIso8601String(),
                'user_id' => $this->updated_by,
                'updated_at' => $this->updated_at->toIso8601String(),
                'updated_by' => $this->whenLoaded(
                    relationship: 'updatedBy',
                    value: fn() => UserResource::make($this->updatedBy)
                ),
            ];
        }

        return $resource;
    }
}
