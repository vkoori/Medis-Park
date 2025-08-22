<?php

namespace Modules\Product\Dto;

use App\Utils\Dto\BaseDto;
use Morilog\Jalali\Jalalian;

class MonthlyCoinDto extends BaseDto
{
    public readonly int $amount;
    public readonly string $jYearMonth;

    public function getMonth(): string
    {
        [$jYear, $jMonth] = explode('-', $this->jYearMonth);

        return (new Jalalian(year: $jYear, month: $jMonth, day: 1))->format('Y-m');
    }
}
