<?php

namespace Modules\Reward\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Reward\Models\Reward;

/**
 * @extends BaseRepository<Reward>
 */
class RewardRepository extends BaseRepository
{
    public function __construct(private Reward $reward) {}

    protected function getModel(): Reward
    {
        return $this->reward;
    }
}
