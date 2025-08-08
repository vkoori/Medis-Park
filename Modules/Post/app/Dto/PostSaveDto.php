<?php

namespace Modules\Post\Dto;

use Carbon\Carbon;
use App\Utils\Dto\BaseDto;
use Illuminate\Http\UploadedFile;

class PostSaveDto extends BaseDto
{
    public readonly string $disk;
    public readonly UploadedFile $banner;
    public readonly string $title;
    public readonly string $content;
    public readonly Carbon $availableAt;
    public readonly Carbon $expiredAt;
}
