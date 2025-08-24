<?php

namespace Modules\Reward\Repositories;

use Modules\Reward\Models\Prize;
use App\Utils\Repository\BaseRepository;

/**
 * @extends BaseRepository<Prize>
 */
class PrizeRepository extends BaseRepository
{
    public function __construct(private Prize $prize) {}

    protected function getModel(): Prize
    {
        return $this->prize;
    }
}
