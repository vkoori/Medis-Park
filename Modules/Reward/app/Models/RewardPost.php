<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class RewardPost extends Model
{
    use Paginatable;

    protected $table = 'reward_posts';

    public $timestamps = false;

    protected $fillable = [
        'amount',
    ];

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }
}
