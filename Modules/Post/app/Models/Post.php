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
        'media_id',
        'title',
        'content',
        'month',
        'updated_by',
    ];

    protected $casts = [
        'content' => CleanHtml::class,
    ];

    public function media(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'media_id');
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
