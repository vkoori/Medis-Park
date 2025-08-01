<?php

namespace Modules\Reward\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Reward\Enums\ProfileLevelEnum;

class ProfileRewardService
{
    use ClassResolver;

    public function getProfileFields(ProfileLevelEnum $level)
    {
        return $this->getRewardProfileRepository()->get(conditions: ['level' => $level]);
    }

    public function getAchievementsOfProfile(int $userId)
    {
        return $this->getRewardProfileRepository()->getAchievements(userId: $userId);
    }

    public function unlockProfile(ProfileLevelEnum $level, int $userId)
    {
        DB::transaction(function () use ($level, $userId) {
            $profileReward = $this->getRewardProfileRepository()->firstOrFail(
                conditions: ['level' => $level],
                relations: ['reward']
            );

            $userAchievement = $this->getRewardUserUnlockedRepository()->create([
                'user_id' => $userId,
                'reward_id' => $profileReward->reward->id,
                'unlocked_at' => now(),
            ]);

            $this->getCoinTransactionService()->transaction(
                userId: $userId,
                amount: $profileReward->amount,
                reason: __(key: "reward::messages.reward_reason.profile", replace: [
                    'level' => $level->translate()
                ]),
                type: TransactionReferenceTypeEnum::REWARD_UNLOCKED,
                referenceId: $userAchievement->id
            );
        });
    }
}
