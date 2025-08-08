<?php

namespace Modules\Post\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPostVisit extends Model
{
    use Paginatable;

    protected $table = 'user_post_visits';

    const CREATED_AT = 'first_visited_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'post_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }
}
