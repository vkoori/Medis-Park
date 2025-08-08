<?php

namespace Modules\Reward\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reward\Models\Reward;
use Modules\Reward\Models\RewardPost;
use Modules\Reward\Enums\RewardTypeEnum;

class ProfileRewardSeeder extends Seeder
{
    public function run(): void
    {
        if (RewardPost::query()->count() == 0) {
            $postReward = RewardPost::query()->create(attributes: ['amount' => 20]);

            Reward::query()->create(attributes: [
                'reward_reference_type' => RewardTypeEnum::UNLOCK_POST,
                'reward_reference_id' => $postReward->id,
            ]);
        }
    }
}
