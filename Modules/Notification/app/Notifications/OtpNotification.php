<?php

namespace Modules\Notification\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Notification\Enums\SmsTemplateEnum;
use Modules\Notification\Notifications\Channels\SmsChannel;
use Modules\Notification\Traits\SmsNotificationTrait;
use Modules\Notification\Traits\ViaQueuesTrait;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;
    use SmsNotificationTrait;
    use ViaQueuesTrait;

    public function __construct(
        private string $code
    ) {
        $this->setSmsTemplate(SmsTemplateEnum::OTP);
    }

    public function via($notifiable): array
    {
        return [SmsChannel::class];
    }

    public function toArray($notifiable): array
    {
        return [
            'code' => $this->code,
        ];
    }
}
