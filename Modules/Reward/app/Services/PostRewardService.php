<?php

namespace Modules\Reward\Services;

use App\Traits\ClassResolver;
use Illuminate\Support\Facades\DB;
use Modules\Coin\Enums\TransactionReferenceTypeEnum;
use Modules\Reward\Enums\BonusTypeEnum;

class PostRewardService
{
    use ClassResolver;

    public function seenPost(int $userId)
    {
        DB::transaction(function () use ($userId) {
            $postReward = $this->getBonusTypeRepository()->firstOrFail(
                conditions: ['type' => BonusTypeEnum::POST],
                relations: ['lastPrice', 'reward']
            );

            $userAchievement = $this->getRewardUserUnlockedRepository()->create([
                'user_id' => $userId,
                'reward_id' => $postReward->reward->id,
                'unlocked_at' => now(),
            ]);

            $this->getCoinTransactionService()->transaction(
                userId: $userId,
                amount: $postReward->lastPrice->amount,
                reason: __(key: "reward::messages.reward_reason.post"),
                type: TransactionReferenceTypeEnum::REWARD_UNLOCKED,
                referenceId: $userAchievement->id
            );
        });
    }
}
