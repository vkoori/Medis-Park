<?php

namespace Modules\Reward\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Reward\Models\Reward;
use Modules\Reward\Models\BonusType;
use Modules\Reward\Enums\BonusTypeEnum;
use Modules\Reward\Enums\RewardTypeEnum;
use Modules\Reward\Models\BonusTypeAmount;

class PostRewardSeeder extends Seeder
{
    public function run(): void
    {
        $bonusType = BonusType::query()->firstOrCreate(attributes: [
            'type' => BonusTypeEnum::POST,
            'sub_type' => null
        ]);

        if ($bonusType->wasRecentlyCreated) {
            BonusTypeAmount::query()->create(attributes: [
                'bonus_type_id' => $bonusType->id,
                'amount' => 20,
                'created_by' => null,
            ]);
        }

        Reward::query()->firstOrCreate(attributes: [
            'reward_reference_type' => RewardTypeEnum::BONUS,
            'reward_reference_id' => $bonusType->id,
        ]);
    }
}
