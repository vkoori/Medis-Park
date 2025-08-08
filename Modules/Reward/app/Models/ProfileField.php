<?php

namespace Modules\Reward\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Modules\Reward\Enums\ProfileLevelEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProfileField extends Model
{
    use Paginatable;

    protected $table = 'profile_fields';

    protected $fillable = [
        'key',
        'level',
        'updated_by',
    ];

    protected $casts = [
        'level' => ProfileLevelEnum::class,
    ];

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
