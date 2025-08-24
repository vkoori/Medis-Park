<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Modules\Product\Models\Product;
use Modules\Reward\Enums\PrizeTypeEnum;
use Illuminate\Database\Eloquent\Model;
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

    protected static function boot()
    {
        parent::boot();

        Relation::morphMap([
            PrizeTypeEnum::COIN    => PrizeCoin::class,
            PrizeTypeEnum::PRODUCT => Product::class,
        ]);
    }

    public function prizeable(): MorphTo
    {
        return $this->morphTo('prizeable', 'type', 'prize_reference_id');
    }
}
