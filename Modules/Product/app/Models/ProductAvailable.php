<?php

namespace Modules\Product\Models;

use App\Traits\Paginatable;
use Modules\Reward\Models\Reward;
use Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductAvailable extends Model
{
    use Paginatable;

    protected $table = 'product_available';

    public $timestamps = false;

    protected $fillable = [
        'month',
        'product_id',
        'ordering'
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
