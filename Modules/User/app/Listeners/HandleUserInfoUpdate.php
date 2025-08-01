<?php

namespace Modules\User\Listeners;

use App\Dto\UserInfoUpdatedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Crm\Jobs\CrmUpdateUserJob;
use Modules\Reward\Jobs\RewardProfileJob;

class HandleUserInfoUpdate implements ShouldQueue
{
    public string $queue = 'user';

    public function handle(UserInfoUpdatedEvent $event): void
    {
        RewardProfileJob::dispatch($event)->onQueue('reward');
        CrmUpdateUserJob::dispatch($event)->onQueue('crm');
    }
}
