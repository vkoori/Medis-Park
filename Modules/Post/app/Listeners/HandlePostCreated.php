<?php

namespace Modules\Post\Listeners;

use App\Dto\PostCreatedEvent;
use Modules\User\Jobs\PostAssignmentJob;
use Illuminate\Contracts\Queue\ShouldQueue;

class HandlePostCreated implements ShouldQueue
{
    public string $queue = 'post';

    public function handle(PostCreatedEvent $event): void
    {
        PostAssignmentJob::dispatch($event)->onQueue('user');
    }
}
