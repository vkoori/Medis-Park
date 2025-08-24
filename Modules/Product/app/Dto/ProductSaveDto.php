<?php

namespace Modules\Product\Dto;

use App\Utils\Dto\BaseDto;
use Illuminate\Http\UploadedFile;

class ProductSaveDto extends BaseDto
{
    public readonly ?string $disk;
    public readonly ?UploadedFile $media;
    public readonly string $title;
    public readonly string $description;
    public readonly int $coinValue;
}
