<?php

namespace Modules\Product\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Modules\Media\Models\Media;
use Modules\Order\Models\Order;
use Modules\Reward\Models\Prize;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Database\Eloquent\Model;
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

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function lastPrice(): HasOne
    {
        return $this->hasOne(ProductPrice::class)->latest();
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function prize()
    {
        return $this->morphOne(Prize::class, 'prizeable', 'type', 'prize_reference_id');
    }
}
