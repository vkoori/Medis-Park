<?php

namespace Modules\Reward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Coin\Models\CoinTransaction;
use Modules\User\Models\User;

class RewardUserUnlock extends Model
{
    protected $table = 'reward_user_unlocks';

    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'reward_id',
        'unlocked_at',
    ];

    protected $casts = [
        'unlocked_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reward(): BelongsTo
    {
        return $this->belongsTo(Reward::class);
    }

    public function coinTransactions(): MorphMany
    {
        return $this->morphMany(CoinTransaction::class, 'reference', 'reference_type', 'reference_id');
    }
}
