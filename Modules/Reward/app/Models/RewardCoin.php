<?php

namespace Modules\Reward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RewardCoin extends Model
{
    protected $table = 'reward_coins';

    public $timestamps = false;

    protected $fillable = [
        'month',
        'amount',
    ];

    protected $casts = [
        'amount' => 'integer',
    ];

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }
}
