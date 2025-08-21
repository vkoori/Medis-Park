<?php

namespace Modules\Product\Models;

use App\Traits\Paginatable;
use Modules\Reward\Models\Reward;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class CoinAvailable extends Model
{
    use Paginatable;

    protected $table = 'coin_available';

    public $timestamps = false;

    protected $fillable = [
        'month',
        'amount',
    ];

    public function reward(): MorphOne
    {
        return $this->morphOne(Reward::class, 'rewardable', 'reward_reference_type', 'reward_reference_id');
    }
}
