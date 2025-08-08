<?php

namespace Modules\Reward\Jobs;

use App\Dto\PostSeenEvent;
use App\Traits\ClassResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RewardPostSeenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ClassResolver;

    public function __construct(private PostSeenEvent $event) {}

    public function handle(): void
    {
        $this->getPostRewardService()->seenPost(userId: $this->event->getUserId());
    }
}
