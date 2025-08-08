<?php

namespace Modules\User\Models;

use App\Traits\Paginatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserInfo extends Model
{
    use Paginatable;

    protected $table = 'user_infos';

    protected $fillable = [
        'user_id',
        'national_code',
        'first_name',
        'last_name',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
