<?php

namespace Modules\Post\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Media\Models\Media;
use Modules\User\Models\User;

class Post extends Model
{
    protected $table = 'posts';

    protected $fillable = [
        'banner',
        'title',
        'content',
        'available_at',
        'expired_at',
        'updated_by',
    ];

    protected $casts = [
        'available_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function banner(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function visitors(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_post_visits',
            'post_id',
            'user_id'
        )->withPivot('first_visited_at');
    }
}
