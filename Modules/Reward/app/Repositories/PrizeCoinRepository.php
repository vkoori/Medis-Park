<?php

namespace Modules\Reward\Repositories;

use Modules\Reward\Models\PrizeCoin;
use App\Utils\Repository\BaseRepository;

/**
 * @extends BaseRepository<Prize>
 */
class PrizeCoinRepository extends BaseRepository
{
    public function __construct(private PrizeCoin $prizeCoin) {}

    protected function getModel(): PrizeCoin
    {
        return $this->prizeCoin;
    }
}
