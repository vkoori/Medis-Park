<?php

namespace Modules\Order\Models;

use App\Traits\Paginatable;
use Modules\Post\Models\Post;
use Modules\Product\Models\CoinAvailable;
use Modules\User\Models\User;
use Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Coin\Models\CoinTransaction;
use Modules\Order\Enums\OrderStatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Order extends Model
{
    use Paginatable;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'product_id',
        'coin_id',
        'post_id',
        'status',
        'coin_value',
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

    public function coinAvailable(): BelongsTo
    {
        return $this->belongsTo(CoinAvailable::class, 'coin_id');
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function coinTransactions(): MorphMany
    {
        return $this->morphMany(CoinTransaction::class, 'reference', 'reference_type', 'reference_id');
    }
}
