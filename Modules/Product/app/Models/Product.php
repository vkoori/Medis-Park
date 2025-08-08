<?php

namespace Modules\Product\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Modules\Media\Models\Media;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Database\Eloquent\Model;
use Modules\Reward\Models\RewardProduct;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use Paginatable;

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
