<?php

namespace Modules\Notification\Models;

use Illuminate\Notifications\Notifiable;
use libphonenumber\PhoneNumber;

readonly class UnknownUserDto
{
    use Notifiable;

    public function __construct(
        public PhoneNumber $mobile,
        public ?string $name = null
    ) {}
}
