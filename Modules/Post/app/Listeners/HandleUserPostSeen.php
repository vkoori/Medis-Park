<?php

namespace Modules\Post\Listeners;

use App\Dto\PostSeenEvent;
use Modules\Reward\Jobs\RewardPostSeenJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandleUserPostSeen implements ShouldQueue
{
    public string $queue = 'post';

    public function handle(PostSeenEvent $event): void
    {
        RewardPostSeenJob::dispatch($event)->onQueue('reward');
    }
}
