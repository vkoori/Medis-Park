<?php

namespace Modules\Post\Http\Resources;

use Illuminate\Http\Request;
use Modules\Media\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class PostCustomerResource extends JsonResource
{
    public function toArray(Request $request)
    {
        return [
            'id' => $this->id,
            'visited' => $this->visited > 0,
            'media_id' => $this->when(
                $this->visited,
                $this->media_id
            ),
            'title' => $this->when(
                $this->visited,
                $this->title
            ),
            'content' => $this->when(
                $this->visited,
                $this->content
            ),
            'created_at' => $this->created_at->toIso8601String(),
            'type' => $this->getType($this->media->mime),
            'media' => $this->when(
                $this->visited,
                MediaResource::make($this->media)
            ),
        ];
    }

    private function getType(string $mime)
    {
        $pos = strpos($mime, '/');

        if ($pos !== false) {
            return substr($mime, 0, $pos);
        } else {
            return $mime;
        }
    }
}
