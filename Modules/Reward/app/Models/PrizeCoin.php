<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;

class PrizeCoin extends Model
{
    use Paginatable;

    protected $table = 'prize_coins';
    public $timestamps = false;

    protected $fillable = [
        'amount',
    ];

    public function prize()
    {
        return $this->morphOne(Prize::class, 'prizeable', 'type', 'prize_reference_id');
    }
}
