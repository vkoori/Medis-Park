<?php

namespace Modules\Crm\Jobs;

use App\Dto\UserInfoUpdatedEvent;
use App\Traits\ClassResolver;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Crm\Dto\UserInfoDto;

class CrmUpdateUserJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    use ClassResolver;

    public function __construct(private UserInfoUpdatedEvent $event) {}

    public function handle(): void
    {
        $crmUserInfo = $this->getCrmService()->getUserInfo(
            mobile: $this->event->getMobile()
        );
        $dto = new UserInfoDto(
            mobile: $this->event->getMobile(),
            nationalCode: $this->event->getNationalCode(),
            firstName: $this->event->getFirstName(),
            lastName: $this->event->getLastName()
        );

        if (!$crmUserInfo) {
            $this->getCrmService()->insertUserInfo(userInfoDto: $dto);
        } else {
            $this->getCrmService()->updateUserInfo(userInfoDto: $dto);
        }
    }
}
