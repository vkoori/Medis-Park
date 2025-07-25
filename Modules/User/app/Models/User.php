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
use Spatie\Permission\Traits\HasRoles;
use Vkoori\JwtAuth\Auth\Traits\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use HasRoles;

    protected ?string $jwtCacheDriver = 'redis_jwt';

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
}
