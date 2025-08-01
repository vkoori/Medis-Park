<?php

namespace Modules\Reward\Database\Seeders;

use Illuminate\Database\Seeder;

class RewardDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            ProfileRewardSeeder::class,
        ]);
    }
}
