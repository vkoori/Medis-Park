<?php

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Mews\Purifier\Casts\CleanHtml;
use Modules\Media\Models\Media;
use Modules\Reward\Models\RewardProduct;
use Modules\User\Models\User;

class Product extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'title',
        'description',
        'media_id',
        'updated_by',
    ];

    protected $casts = [
        'description' => CleanHtml::class,
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function lastPrice(): HasOne
    {
        return $this->hasOne(ProductPrice::class)->latest();
    }

    public function rewardProducts(): HasMany
    {
        return $this->hasMany(RewardProduct::class);
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
