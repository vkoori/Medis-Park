<?php

namespace Modules\Reward\Jobs;

use App\Dto\UserInfoUpdatedEvent;
use App\Traits\ClassResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RewardProfileJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use ClassResolver;

    public function __construct(private UserInfoUpdatedEvent $event) {}

    public function handle(): void
    {
        dd(
            'calc reward',
            $this->event
        );
    }
}
