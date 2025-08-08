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
            'banner' => [
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
            'available_at' => [
                $isUpdate ? 'nullable' : 'required',
                'date_format:Y-m-d\\TH:i:sP',
                'after_or_equal:now',
            ],
            'expired_at' => [
                $isUpdate ? 'nullable' : 'required',
                'date_format:Y-m-d\\TH:i:sP',
            ],
        ];
    }

    public function withValidator($validator)
    {
        $validator->sometimes(
            'expired_at',
            'after:available_at',
            fn($input): bool => !empty($input->available_at)
        );
    }
}
