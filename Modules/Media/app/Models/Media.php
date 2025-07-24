<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    const UPDATED_AT = null;

    protected $table = 'medias';

    protected $fillable = [
        'disk',
        'bucket',
        'path',
        'name',
        'original_name',
        'mime',
        'ext',
        'size',
        'width',
        'height',
        'is_private',
    ];

    protected $casts = [
        'size'       => 'integer',
        'width'      => 'integer',
        'height'     => 'integer',
        'is_private' => 'boolean',
    ];
}
