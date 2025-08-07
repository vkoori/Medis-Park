<?php

namespace Modules\Media\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

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

    protected function path(): Attribute
    {
        $storage = Storage::disk($this->disk);

        return new Attribute(
            get: fn($value) => $this->getUrl($storage, $value)
        );
    }

    private function getUrl($storage, $value)
    {
        if ($this->is_private && method_exists($storage, 'temporaryUrl')) {
            try {
                return $storage->temporaryUrl($value, now()->addMinutes(30));
            } catch (\Exception $e) {
                return $storage->url("{$this->bucket}/{$value}");
            }
        }

        return method_exists($storage, 'url')
            ? $storage->url("{$this->bucket}/{$value}")
            : $value;
    }
}
