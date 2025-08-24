<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Reward\Enums\PrizeUnlockTypeEnum;

class PrizeUnlock extends Model
{
    use Paginatable;

    protected $table = 'prize_unlocks';
    const UPDATED_AT = null;

    protected $fillable = [
        'prize_id',
        'type',
        'user_id',
    ];
    protected $casts = [
        'type' => PrizeUnlockTypeEnum::class,
    ];

    public function prize()
    {
        return $this->belongsTo(Prize::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
