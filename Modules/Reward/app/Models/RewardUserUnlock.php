<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Coin\Models\CoinTransaction;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class RewardUserUnlock extends Model
{
    use Paginatable;

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
