<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RewardProduct extends Model
{
    use Paginatable;

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
