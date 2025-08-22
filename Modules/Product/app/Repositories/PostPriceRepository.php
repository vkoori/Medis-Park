<?php

namespace Modules\Product\Repositories;

use Modules\Product\Models\PostPrice;
use App\Utils\Repository\BaseRepository;

/**
 * @extends BaseRepository<PostPrice>
 */
class PostPriceRepository extends BaseRepository
{
    public function __construct(private PostPrice $postPrice) {}

    protected function getModel(): PostPrice
    {
        return $this->postPrice;
    }
}
