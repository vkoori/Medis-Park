<?php

namespace Modules\Product\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Product\Models\PostPrice;

class PostPriceSeeder extends Seeder
{
    public function run(): void
    {
        if (PostPrice::query()->count() == 0) {
            PostPrice::query()->create(attributes: [
                'coin_value' => 20,
            ]);
        }
    }
}
