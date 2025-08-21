<?php

namespace Modules\Reward\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Reward\Enums\BonusTypeEnum;
use Modules\Reward\Enums\ProfileLevelEnum;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;

class ProfileRewardService
{
    use ClassResolver;

    public function getProfileFields(ProfileLevelEnum $level)
    {
        return $this->getProfileFieldRepository()->get(conditions: ['level' => $level]);
    }

    public function getAchievementsOfProfile(int $userId)
    {
        return $this->getBonusTypeRepository()->getAchievements(userId: $userId, type: BonusTypeEnum::PROFILE);
    }

    public function unlockProfile(ProfileLevelEnum $level, int $userId)
    {
        DB::transaction(function () use ($level, $userId) {
            $profileReward = $this->getBonusTypeRepository()->firstOrFail(
                conditions: [
                    'type' => BonusTypeEnum::PROFILE,
                    'sub_type' => $level
                ],
                relations: ['lastPrice', 'reward']
            );

            $userAchievement = $this->getRewardUserUnlockedRepository()->create([
                'user_id' => $userId,
                'reward_id' => $profileReward->reward->id,
                'unlocked_at' => now(),
            ]);

            $this->getCoinTransactionService()->transaction(
                userId: $userId,
                amount: $profileReward->lastPrice->amount,
                reason: __(key: "reward::messages.reward_reason.profile", replace: [
                    'level' => $level->translate()['translate']
                ]),
                type: TransactionReferenceTypeEnum::REWARD_UNLOCKED,
                referenceId: $userAchievement->id
            );
        });
    }
}
