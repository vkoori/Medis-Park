<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonusTypeAmount extends Model
{
    use Paginatable;

    protected $table = 'bonus_type_amounts';
    const UPDATED_AT = null;

    protected $fillable = [
        'bonus_type_id',
        'amount',
        'created_by',
    ];

    public function bonusType(): BelongsTo
    {
        return $this->belongsTo(BonusType::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
