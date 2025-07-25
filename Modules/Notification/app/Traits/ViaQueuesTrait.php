<?php

namespace Modules\Notification\Traits;

use Modules\Notification\Notifications\Channels\SmsChannel;

trait ViaQueuesTrait
{
    public function viaQueues()
    {
        $isAdvertising = $this->advertising ?? false;

        return [
            SmsChannel::class => $isAdvertising
                ? config('notification.queue.sms_advertising')
                : config('notification.queue.sms_product'),
        ];
    }
}
