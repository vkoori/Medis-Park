<?php

namespace Modules\Product\Dto;

use App\Utils\Dto\BaseDto;
use Morilog\Jalali\Jalalian;

class MonthlyComponentDto extends BaseDto
{
    public readonly int $componentId;
    public readonly string $jYearMonth;

    public function getMonth(): string
    {
        [$jYear, $jMonth] = explode('-', $this->jYearMonth);

        return (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format('Y-m');
    }
}
