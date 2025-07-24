<?php

namespace Modules\Order\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Coin\Models\CoinTransaction;
use Modules\User\Models\User;
use Modules\Product\Models\Product;
use Modules\Order\Enums\OrderStatusEnum;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'product_id',
        'status',
        'used_at',
    ];

    protected $casts = [
        'status' => OrderStatusEnum::class,
        'used_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function coinTransactions(): MorphMany
    {
        return $this->morphMany(CoinTransaction::class, 'reference', 'reference_type', 'reference_id');
    }
}
