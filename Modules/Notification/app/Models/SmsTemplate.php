<?php

namespace Modules\Notification\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Notification\Enums\SmsTemplateEnum;

class SmsTemplate extends Model
{
    use Paginatable;

    protected $table = 'sms_templates';

    public $timestamps = false;

    protected $casts = [
        'template' => SmsTemplateEnum::class,
    ];
}
