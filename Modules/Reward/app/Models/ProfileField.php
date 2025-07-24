<?php

namespace Modules\Reward\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Reward\Enums\ProfileLevelEnum;
use Modules\User\Models\User;

class ProfileField extends Model
{
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
