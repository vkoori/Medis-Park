<?php

namespace Modules\Order\Dto;

use App\Utils\Dto\BaseDto;

class OrderFilterDto extends BaseDto
{
    public readonly ?string $mobile;
    public readonly ?bool $unused;
}
