<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use Modules\Reward\Enums\ProfileLevelEnum;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RewardProfile extends Model
{
    use Paginatable;

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
