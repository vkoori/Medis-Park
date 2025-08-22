<?php

namespace Modules\Product\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Product\Models\CoinAvailable;

/**
 * @extends BaseRepository<CoinAvailable>
 */
class CoinAvailableRepository extends BaseRepository
{
    public function __construct(private CoinAvailable $model) {}

    protected function getModel(): CoinAvailable
    {
        return $this->model;
    }
}
