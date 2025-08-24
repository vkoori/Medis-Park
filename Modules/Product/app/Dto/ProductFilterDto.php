<?php

namespace Modules\Product\Dto;

use App\Utils\Dto\BaseDto;

class ProductFilterDto extends BaseDto
{
    public readonly ?string $title;
}
