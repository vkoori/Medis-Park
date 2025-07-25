<?php

namespace Modules\Notification\Services\SmsProviders;

use Illuminate\Support\Facades\Http;
use Modules\Notification\Exceptions\SmsNotificationException;

class Kavenegar implements SmsProviderInterface
{
    protected string $apiKey;
    protected string $baseUrl;
    protected ?string $sender;

    public function __construct()
    {
        $this->baseUrl = config('notification.smsProviders.kavenegar.baseUrl');
        $this->apiKey = config('notification.smsProviders.kavenegar.apiKey');
        $this->sender = config('notification.smsProviders.kavenegar.sender');
    }

    public function send(string|array $receptor, string $message)
    {
        $receptor = is_array($receptor) ? implode(',', $receptor) : $receptor;

        $response = Http::timeout(5)->asForm()->post(
            url: $this->baseUrl . '/v1/' . $this->apiKey . '/sms/send.json',
            data: [
                'receptor' => $receptor,
                'message' => $message,
                ...($this->sender ? ['sender' => $this->sender] : [])
            ]
        );

        if ($response->failed()) {
            throw new SmsNotificationException(previous: $response->toException());
        }

        return $response->json()['entries'];
    }
}
