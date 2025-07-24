<?php

namespace Modules\Coin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Order\Models\Order;
use Modules\Reward\Models\RewardUserUnlock;
use Modules\User\Models\User;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Coin\Enums\TransactionStatusEnum;

class CoinTransaction extends Model
{
    protected $table = 'coin_transactions';

    protected $fillable = [
        'user_id',
        'amount',
        'reason',
        'reference_type',
        'reference_id',
        'status',
    ];

    protected $casts = [
        'amount'         => 'integer',
        'reference_type' => TransactionReferenceTypeEnum::class,
        'status'         => TransactionStatusEnum::class,
    ];

    protected static function boot(): void
    {
        parent::boot();

        Relation::morphMap([
            TransactionReferenceTypeEnum::REWARD_UNLOCKED->value => RewardUserUnlock::class,
            TransactionReferenceTypeEnum::ORDER->value => Order::class,
            TransactionReferenceTypeEnum::CRM->value => CrmReference::class,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reference(): MorphTo
    {
        return $this->morphTo(null, 'reference_type', 'reference_id');
    }
}
