<?php

namespace Modules\Post\Dto;

use App\Utils\Dto\BaseDto;
use Morilog\Jalali\Jalalian;

class PostFilterDto extends BaseDto
{
    public readonly ?string $title;
    public readonly string $month;

    public function getStartOfMonth(): ?Jalalian
    {
        if (empty($this->month)) {
            return null;
        }

        [$jYear, $jMonth] = explode('-', $this->month);
        return new Jalalian(year: $jYear, month: $jMonth, day: 1);
    }
}
