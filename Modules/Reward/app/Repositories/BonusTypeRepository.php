<?php

namespace Modules\Reward\Repositories;

use Modules\Reward\Models\BonusType;
use Modules\Reward\Enums\BonusTypeEnum;
use App\Utils\Repository\BaseRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<BonusType>
 */
class BonusTypeRepository extends BaseRepository
{
    public function __construct(private BonusType $rewardProfile) {}

    protected function getModel(): BonusType
    {
        return $this->rewardProfile;
    }

    /**
     * @param int $userId
     * @return Collection<int, BonusType>
     */
    public function getAchievements(int $userId, BonusTypeEnum $type): Collection
    {
        return $this->getModel()
            ->where('type', $type)
            ->whereHas('reward.rewardUnlocks', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();
    }
}
