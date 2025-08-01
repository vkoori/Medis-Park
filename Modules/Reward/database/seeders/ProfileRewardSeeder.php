<?php

namespace Modules\Reward\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reward\Enums\ProfileFieldEnum;
use Modules\Reward\Enums\ProfileLevelEnum;
use Modules\Reward\Enums\RewardTypeEnum;
use Modules\Reward\Models\ProfileField;
use Modules\Reward\Models\Reward;
use Modules\Reward\Models\RewardProfile;

class ProfileRewardSeeder extends Seeder
{
    public function run(): void
    {
        $profileLevels = ProfileLevelEnum::cases();

        foreach ($profileLevels as $profileLevel) {
            $rewardProfile = RewardProfile::query()->firstOrCreate(
                attributes: ['level' => $profileLevel],
                values: ['amount' => $profileLevel->defaultReward()]
            );

            Reward::query()->firstOrCreate(
                attributes: [
                    'reward_reference_type' => RewardTypeEnum::PROFILE,
                    'reward_reference_id' => $rewardProfile->id,
                ],
                values: []
            );
        }

        $profileFields = ProfileFieldEnum::cases();

        foreach ($profileFields as $profileField) {
            ProfileField::query()->firstOrCreate(
                attributes: ['key' => $profileField->value],
                values: ['level' => $profileField->defaultLevel()]
            );
        }
    }
}
