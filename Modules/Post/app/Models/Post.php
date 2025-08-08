<?php

namespace Modules\Post\Models;

use App\Traits\Paginatable;
use Modules\User\Models\User;
use Modules\Media\Models\Media;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use Paginatable;

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
        'content' => CleanHtml::class,
        'available_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'banner');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function seen(): HasMany
    {
        return $this->hasMany(UserPostVisit::class);
    }
}
