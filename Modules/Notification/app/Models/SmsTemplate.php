<?php

namespace Modules\Notification\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\Notification\Enums\SmsTemplateEnum;

class SmsTemplate extends Model
{
    protected $table = 'sms_templates';

    public $timestamps = false;

    protected $casts = [
        'template' => SmsTemplateEnum::class,
    ];
}
