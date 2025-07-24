<?php

namespace Modules\Reward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Reward\Enums\RewardTypeEnum;
use Modules\User\Models\User;

class Reward extends Model
{
    use SoftDeletes;

    const UPDATED_AT = null;

    protected $table = 'rewards';

    protected $fillable = [
        'reward_reference_type',
        'reward_reference_id',
        'created_by',
        'deleted_by',
    ];

    protected $casts = [
        'reward_reference_type' => RewardTypeEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        Relation::morphMap([
            RewardTypeEnum::MONTHLY_COIN->value => RewardCoin::class,
            RewardTypeEnum::MONTHLY_PRODUCT->value => RewardProduct::class,
            RewardTypeEnum::PROFILE->value => RewardProfile::class,
        ]);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function rewardable(): MorphTo
    {
        return $this->morphTo(null, 'reward_reference_type', 'reward_reference_id');
    }

    public function rewardUnlocks(): HasMany
    {
        return $this->hasMany(RewardUserUnlock::class);
    }
}
