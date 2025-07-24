<?php

namespace Modules\Reward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Modules\Reward\Enums\ProfileLevelEnum;

class RewardProfile extends Model
{
    protected $table = 'reward_profiles';

    public $timestamps = false;

    protected $fillable = [
        'level',
        'amount',
    ];

    protected $casts = [
        'level' => ProfileLevelEnum::class,
        'amount' => 'integer',
    ];

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }
}
