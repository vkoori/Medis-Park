<?php

namespace Modules\User\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\User\Enums\UserStatusEnum;
use Vkoori\JwtAuth\Auth\Traits\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;

    protected ?string $jwtCacheDriver = 'redis_jwt';

    protected $fillable = [
        'mobile',
        'status',
    ];

    protected $casts = [
        'status' => UserStatusEnum::class,
    ];
}
