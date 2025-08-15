<?php

namespace Modules\User\Jobs;

use App\Dto\PostCreatedEvent;
use App\Traits\ClassResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PostAssignmentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, ClassResolver;

    public function __construct(private PostCreatedEvent $event) {}

    public function handle(): void
    {
        // $this->getUserRepository()
        // $this->getPostRewardService()->seenPost(userId: $this->event->getUserId());
    }
}
