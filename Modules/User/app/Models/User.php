<?php

namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Coin\Models\CoinTransaction;
use Modules\Order\Models\Order;
use Modules\Post\Models\Post;
use Modules\Reward\Models\RewardUserUnlock;
use Modules\User\Casts\PhoneNumberCast;
use Modules\User\Enums\UserStatusEnum;
use Modules\User\Support\FormattedPhoneNumber;
use Spatie\Permission\Traits\HasRoles;
use Vkoori\JwtAuth\Auth\Traits\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasRoles;

    public ?string $jwtCacheDriver = 'redis_jwt';

    protected $guard_name = 'api';

    protected $table = 'users';

    protected $fillable = [
        'mobile',
        'status',
    ];

    protected $casts = [
        'mobile' => PhoneNumberCast::class,
        'status' => UserStatusEnum::class,
    ];

    public function otps(): HasMany
    {
        return $this->hasMany(UserOtp::class);
    }

    public function info(): HasOne
    {
        return $this->hasOne(UserInfo::class);
    }

    public function rewardUnlocks(): HasMany
    {
        return $this->hasMany(RewardUserUnlock::class);
    }

    public function visitedPosts(): BelongsToMany
    {
        return $this->belongsToMany(
            Post::class,
            'user_post_visits',
            'user_id',
            'post_id'
        )->withPivot('first_visited_at');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function coinTransactions(): HasMany
    {
        return $this->hasMany(CoinTransaction::class);
    }

    public function scopeWhereMobile(
        Builder $query,
        string|PhoneNumber|FormattedPhoneNumber $mobileInput,
        ?string $regionCode = null
    ): Builder {
        $phoneUtil = PhoneNumberUtil::getInstance();
        $e164Mobile = null;
        $phoneNumberObject = null;

        try {
            if ($mobileInput instanceof PhoneNumber) {
                $phoneNumberObject = $mobileInput;
            } elseif ($mobileInput instanceof FormattedPhoneNumber) {
                $phoneNumberObject = $mobileInput->toRawPhoneNumber();
            } else {
                $effectiveRegionCode = $regionCode ?? 'IR';
                $phoneNumberObject = $phoneUtil->parse($mobileInput, $effectiveRegionCode);
            }

            if ($phoneNumberObject) {
                $e164Mobile = $phoneUtil->format($phoneNumberObject, PhoneNumberFormat::E164);
            } else {
                return $query->whereRaw('1 = 0');
            }
        } catch (NumberParseException $e) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('mobile', $e164Mobile);
    }
}
