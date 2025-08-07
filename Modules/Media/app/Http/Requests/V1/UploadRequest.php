<?php

namespace Modules\Media\Http\Requests\V1;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Media\Enums\UploadableDiskEnum;

class UploadRequest extends FormRequest
{
    public function rules()
    {
        return [
            'disk' => [
                'required',
                Rule::enum(UploadableDiskEnum::class)
            ],
            'file' => [
                'required',
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
                        # PDFs
                        'application/pdf',
                        # Word, Excel, PowerPoint
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'application/vnd.ms-excel',
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'application/vnd.ms-powerpoint',
                        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                        # Text & code files
                        'text/plain',
                        'text/csv',
                        'text/xml',
                        # Archives
                        'application/zip',
                        'application/x-7z-compressed',
                        'application/x-rar-compressed',
                        'application/x-tar'
                    ])
            ],
        ];
    }
}
