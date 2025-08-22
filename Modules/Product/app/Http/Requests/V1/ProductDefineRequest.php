<?php

namespace Modules\Product\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Enums\UploadableDiskEnum;

class ProductDefineRequest extends FormRequest
{
    public function rules()
    {
        return [
            'disk' => [
                'required_with:media',
                Rule::in(UploadableDiskEnum::private())
            ],
            'media' => [
                'required_with:disk',
                Rule::file()
                    ->max(10240)
                    ->types([
                        # Images
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                        'image/webp',
                        'image/svg+xml',
                        'image/bmp',
                        'image/tiff',
                        # Videos
                        'video/mp4',
                        'video/quicktime',
                        'video/x-msvideo',
                        'video/x-matroska',
                        'video/mpeg',
                        # Audio
                        'audio/mpeg',
                        'audio/ogg',
                        'audio/wav',
                        'audio/x-wav',
                        'audio/webm',
                    ])
            ],
            'title' => [
                'required',
                'string',
                'max:100'
            ],
            'description' => [
                'required',
                'string',
                'max:65535'
            ],
            'coin_value' => [
                'required',
                'integer'
            ]
        ];
    }
}
