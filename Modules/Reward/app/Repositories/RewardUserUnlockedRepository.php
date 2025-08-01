<?php

namespace Modules\Reward\Repositories;

use App\Utils\Repository\BaseRepository;
use Modules\Reward\Models\RewardUserUnlock;

/**
 * @extends BaseRepository<RewardUserUnlock>
 */
class RewardUserUnlockedRepository extends BaseRepository
{
    public function __construct(private RewardUserUnlock $rewardUserUnlock) {}

    protected function getModel(): RewardUserUnlock
    {
        return $this->rewardUserUnlock;
    }
}
