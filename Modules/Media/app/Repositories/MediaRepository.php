<?php

namespace Modules\Media\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Media\Models\Media;

/**
 * @extends BaseRepository<Media>
 */
class MediaRepository extends BaseRepository
{
    public function __construct(private Media $media) {}

    protected function getModel(): Media
    {
        return $this->media;
    }
}
