<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPrice extends Model
{
    protected $table = 'product_prices';

    const UPDATED_AT = null;

    protected $fillable = [
        'product_id',
        'coin_value',
        'created_at',
    ];

    protected $casts = [
        'coin_value' => 'integer',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
