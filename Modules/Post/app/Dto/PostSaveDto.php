<?php

namespace Modules\Post\Dto;

use App\Utils\Dto\BaseDto;
use Morilog\Jalali\Jalalian;
use Illuminate\Http\UploadedFile;

class PostSaveDto extends BaseDto
{
    public readonly string $disk;
    public readonly UploadedFile $media;
    public readonly string $title;
    public readonly string $content;
    public readonly string $jYearMonth;

    public function getStartOfMonth(): ?Jalalian
    {
        if (empty($this->jYearMonth)) {
            return null;
        }

        [$jYear, $jMonth] = explode('-', $this->jYearMonth);
        return new Jalalian(year: $jYear, month: $jMonth, day: 1);
    }
}
