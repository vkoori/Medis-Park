<?php

namespace Modules\Reward\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reward\Models\Reward;
use Modules\Reward\Models\BonusType;
use Modules\Reward\Enums\BonusTypeEnum;
use Modules\Reward\Models\ProfileField;
use Modules\Reward\Enums\RewardTypeEnum;
use Modules\Reward\Enums\ProfileFieldEnum;
use Modules\Reward\Enums\ProfileLevelEnum;
use Modules\Reward\Models\BonusTypeAmount;

class ProfileRewardSeeder extends Seeder
{
    public function run(): void
    {
        $profileLevels = ProfileLevelEnum::cases();

        foreach ($profileLevels as $profileLevel) {
            $bonusType = BonusType::query()->firstOrCreate(attributes: [
                'type' => BonusTypeEnum::PROFILE,
                'sub_type' => $profileLevel
            ]);

            if ($bonusType->wasRecentlyCreated) {
                BonusTypeAmount::query()->create(attributes: [
                    'bonus_type_id' => $bonusType->id,
                    'amount' => $profileLevel->defaultReward(),
                    'created_by' => null,
                ]);
            }

            Reward::query()->firstOrCreate(attributes: [
                'reward_reference_type' => RewardTypeEnum::BONUS,
                'reward_reference_id' => $bonusType->id,
            ]);
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
