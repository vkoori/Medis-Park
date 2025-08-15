<?php

namespace Modules\Post\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Enums\UploadableDiskEnum;

class PostSaveRequest extends FormRequest
{
    public function rules()
    {
        $isUpdate = in_array($this->method(), ['PUT', 'PATCH']);

        return [
            'disk' => [
                $isUpdate ? 'required_with:banner' : 'required',
                Rule::in(UploadableDiskEnum::private())
            ],
            'media' => [
                $isUpdate ? 'required_with:disk' : 'required',
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
                $isUpdate ? 'nullable' : 'required',
                'string',
                'max:100'
            ],
            'content' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'max:65535'
            ],
            'j_year_month' => $isUpdate
                ? ['prohibited']
                : ['required', 'regex:/^\d{4}-(0?[1-9]|1[0-2])$/'],
        ];
    }
}
