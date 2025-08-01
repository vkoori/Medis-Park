<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Hash;
use Modules\User\Enums\OtpTypeEnum;

class UserOtp extends Model
{
    protected $table = 'user_otps';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'otp_hash',
        'type',
        'used',
        'expires_at',
    ];

    protected $casts = [
        'otp_hash' => 'hashed',
        'used' => 'boolean',
        'expires_at' => 'datetime',
        'type' => OtpTypeEnum::class,
    ];

    protected function otpHash(): Attribute
    {
        return Attribute::make(
            set: fn($value) => Hash::make($value),
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
