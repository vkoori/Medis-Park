<?php

namespace Modules\Reward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Product\Models\Product;

class RewardProduct extends Model
{
    protected $table = 'reward_products';

    public $timestamps = false;

    protected $fillable = [
        'month',
        'product_id',
    ];

    protected $casts = [
        'month' => 'string',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }
}
