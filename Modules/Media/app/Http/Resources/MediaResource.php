<?php

namespace Modules\Media\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'disk' => $this->disk,
            'bucket' => $this->bucket,
            'path' => $this->path,
            'name' => $this->name,
            'original_name' => $this->original_name,
            'mime' => $this->mime,
            'ext' => $this->ext,
            'size' => $this->size,
            'width' => $this->width,
            'height' => $this->height,
            'is_private' => $this->is_private,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
