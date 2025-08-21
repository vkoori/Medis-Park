<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Reward\Enums\BonusTypeEnum;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class BonusType extends Model
{
    use Paginatable;

    protected $table = 'bonus_types';

    public $timestamps = false;

    protected $fillable = [
        'type',
        'sub_type',
    ];

    protected $casts = [
        'type' => BonusTypeEnum::class,
    ];

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(BonusTypeAmount::class);
    }

    public function lastPrice(): HasOne
    {
        return $this->hasOne(BonusTypeAmount::class)->latest();
    }
}
