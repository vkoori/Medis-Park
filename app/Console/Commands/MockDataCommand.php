<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\UploadedFile;
use Modules\Media\Services\MediaService;
use Modules\Post\Models\Post;
use Modules\Product\Models\Product;
use Modules\Reward\Enums\PrizeTypeEnum;
use Modules\Reward\Models\Prize;
use Modules\Reward\Models\PrizeCoin;
use Morilog\Jalali\Jalalian;

class MockDataCommand extends Command
{
    protected $signature = 'seed:mock-data {month?}';

    protected $description = 'Generate Some Mock Data. [usage: php artisan seed:mock-data 1404-06]';

    protected array $words = [
        'lorem',
        'ipsum',
        'dolor',
        'sit',
        'amet',
        'consectetur',
        'adipiscing',
        'elit',
        'sed',
        'do',
        'eiusmod',
        'tempor',
        'incididunt',
        'ut',
        'labore',
        'et',
        'dolore',
        'magna',
        'aliqua',
        'ut',
        'enim',
        'ad',
        'minim',
        'veniam',
        'quis',
        'nostrud',
        'exercitation',
        'ullamco',
        'laboris',
        'nisi',
        'aliquip',
        'ex',
        'ea',
        'commodo',
        'consequat'
    ];

    public function handle()
    {
        $this->fakePosts();
        $this->fakeProducts();
        $this->fakePrizes();

        $this->info("Fake data created successfully.");
    }

    private function fakePosts()
    {
        $path = storage_path('app/mock/sample.jpg');

        if (!file_exists($path)) {
            $this->error("Sample file not found at {$path}");
            return;
        }

        $uploadedFile = new UploadedFile(
            $path,
            'sample.jpg',
            mime_content_type($path),
            null,
            true
        );

        /** @var MediaService $mediaService */
        $mediaService = app(MediaService::class);
        $media = $mediaService->upload($uploadedFile, 's3_private');

        // Use month from input or fallback to current
        $monthInput = $this->argument('month');
        if ($monthInput) {
            // expecting format: YYYY-MM
            [$year, $month] = explode('-', $monthInput);
            $jalali = new Jalalian((int)$year, (int)$month, 1);
        } else {
            $jalali = Jalalian::now();
        }

        $daysInMonth = $jalali->getMonthDays();
        $exists = Post::query()->where('month', $jalali->format('Y-m'))->count();

        for ($i = 1; $i <= $daysInMonth - $exists; $i++) {
            $title = $this->makeSentence(5, 8);
            $content = '<p>' . implode('</p><p>', [
                $this->makeSentence(20, 30),
                $this->makeSentence(15, 25),
                $this->makeSentence(10, 20),
            ]) . '</p>';

            $post = Post::create([
                'media_id'   => $media->id,
                'title'      => $title,
                'content'    => $content,
                'month'      => $jalali->format('Y-m'),
                'updated_by' => 1,
            ]);

            $this->info("Fake post created with ID: {$post->id}");
        }
    }

    private function fakeProducts()
    {
        $path = storage_path('app/mock/hair_dryer.jpg');
        if (!file_exists($path)) {
            $this->error("Sample product image not found at {$path}");
            return;
        }

        $uploadedFile = new UploadedFile(
            $path,
            'hair_dryer.jpg',
            mime_content_type($path),
            null,
            true
        );

        /** @var MediaService $mediaService */
        $mediaService = app(MediaService::class);
        $media = $mediaService->upload($uploadedFile, 's3_private');

        for ($i = 1; $i <= 15; $i++) {
            $title = "Hair Dryer Model {$i}";
            $description = '<p>' . $this->makeSentence(15, 25) . '</p>';

            $product = Product::create([
                'title'       => $title,
                'description' => $description,
                'media_id'    => $media->id,
                'updated_by'  => 1,
            ]);

            $product->prices()->create([
                'coin_value' => rand(10, 50),
            ]);

            $this->info("Fake product created with ID: {$product->id}");
        }
    }

    private function fakePrizes()
    {
        $monthInput = $this->argument('month');
        if ($monthInput) {
            [$year, $month] = explode('-', $monthInput);
            $jalali = new Jalalian((int)$year, (int)$month, 1);
        } else {
            $jalali = Jalalian::now();
        }
        $month = $jalali->format('Y-m');

        $count = rand(7, 12); // total number of prizes for the month

        $usedProductIds = Prize::where('month', $month)
            ->where('type', PrizeTypeEnum::PRODUCT)
            ->pluck('prize_reference_id')
            ->toArray();

        for ($i = 1; $i <= $count; $i++) {
            $availableProducts = Product::query()->whereNotIn('id', $usedProductIds)->get();

            if ($availableProducts->isNotEmpty() && rand(0, 1) === 0) {
                $product = $availableProducts->random();

                Prize::create([
                    'type'               => PrizeTypeEnum::PRODUCT,
                    'prize_reference_id' => $product->id,
                    'month'              => $month,
                    'ordering'           => $i,
                ]);

                $usedProductIds[] = $product->id;

                $this->info("Prize created: Product ID {$product->id} for {$month}");
            } else {
                $coin = PrizeCoin::create([
                    'amount' => rand(50, 300),
                ]);

                Prize::create([
                    'type'               => PrizeTypeEnum::COIN,
                    'prize_reference_id' => $coin->id,
                    'month'              => $month,
                    'ordering'           => $i,
                ]);

                $this->info("Prize created: Coin {$coin->amount} for {$month}");
            }
        }
    }

    private function makeSentence(int $minWords, int $maxWords): string
    {
        $wordCount = rand($minWords, $maxWords);
        shuffle($this->words);
        $words = array_slice($this->words, 0, $wordCount);
        return ucfirst(implode(' ', $words)) . '.';
    }
}
