<?php

namespace Modules\Post\Dto;

use Carbon\Carbon;
use App\Utils\Dto\BaseDto;

class PostFilterDto extends BaseDto
{
    public readonly ?string $title;
    public readonly ?Carbon $from;
    public readonly ?Carbon $to;
}
