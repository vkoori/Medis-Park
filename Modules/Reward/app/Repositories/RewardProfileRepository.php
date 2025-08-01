<?php

namespace Modules\Reward\Repositories;

use App\Utils\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Modules\Reward\Models\RewardProfile;

/**
 * @extends BaseRepository<RewardProfile>
 */
class RewardProfileRepository extends BaseRepository
{
    public function __construct(private RewardProfile $rewardProfile) {}

    protected function getModel(): RewardProfile
    {
        return $this->rewardProfile;
    }

    /**
     * @param int $userId
     * @return Collection<int, RewardProfile>
     */
    public function getAchievements(int $userId): Collection
    {
        return $this->getModel()
            ->whereHas('reward.rewardUnlocks', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }
}
