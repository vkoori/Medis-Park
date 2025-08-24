<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Product\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Modules\Reward\Enums\PrizeTypeEnum;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;

class Prize extends Model
{
    use Paginatable;

    protected $table = 'prizes';
    protected $fillable = [
        'type',
        'prize_reference_id',
        'month',
        'ordering',
    ];
    protected $casts = [
        'type' => PrizeTypeEnum::class,
    ];

    protected static function boot()
    {
        parent::boot();

        Relation::morphMap([
            PrizeTypeEnum::COIN->value    => PrizeCoin::class,
            PrizeTypeEnum::PRODUCT->value => Product::class,
        ]);
    }

    public function prizeable(): MorphTo
    {
        return $this->morphTo('prizeable', 'type', 'prize_reference_id');
    }

    public function prizeUnlocks()
    {
        return $this->hasMany(PrizeUnlock::class);
    }

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }
}
