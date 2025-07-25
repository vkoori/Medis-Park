<?php

namespace Modules\Notification\Notifications\Channels;

use App\Exceptions\NotImplementedException;
use App\Utils\CacheKeys;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Modules\Notification\Dto\SmsableDto;
use Modules\Notification\Enums\SmsSenderNumberEnum;
use Modules\Notification\Exceptions\NotAvailableProviderException;
use Modules\Notification\Services\SmsProviders\SmsProviderInterface;
use Modules\Notification\Traits\SmsNotificationTrait;

class SmsChannel
{
    private const MAX_PROVIDER_ERRORS = 5;
    private const ERROR_CACHE_DURATION_MINUTES = 5;

    /**
     * @param \Illuminate\Notifications\Notifiable $notifiable
     * @param Notification|SmsNotificationTrait $notification
     * @return void
     * @throws NotImplementedException
     * @throws NotAvailableProviderException
     * @throws \Throwable
     */
    public function send($notifiable, Notification $notification): void
    {
        if (!method_exists($notification, 'toSms')) {
            $notificationClass = get_class($notification);
            throw new NotImplementedException(
                message: "Notification {$notificationClass} does not implement SmsNotificationTrait."
            );
        }

        /** @var SmsableDto $smsable */
        $smsable = $notification->toSms($notifiable);

        if (app()->isProduction()) {
            $this->sendProductionSms($smsable);
        } else {
            $this->logDevelopmentSms($smsable);
        }
    }

    private function sendProductionSms(SmsableDto $smsable): void
    {
        try {
            $provider = $this->resolveSmsProvider($smsable);
            $provider->send(
                receptor: $smsable->getReceptor(),
                message: $smsable->getMessage()
            );
        } catch (NotAvailableProviderException $e) {
            throw $e;
        } catch (\Throwable $e) {
            $this->recordProviderError(get_class($provider));
            throw $e;
        }
    }

    private function logDevelopmentSms(SmsableDto $smsable): void
    {
        Log::channel('sms')->info('SMS Sent (Development)', [
            'receptor' => $smsable->getReceptor(),
            'message' => $smsable->getMessage(),
            'is_advertising' => $smsable->isAdvertising(),
            'sender' => $smsable->getSender()?->name,
        ]);
    }

    protected function resolveSmsProvider(SmsableDto $smsableDto): SmsProviderInterface
    {
        $isAdvertising = $smsableDto->isAdvertising();
        $currentProvider = $smsableDto->getSender();

        $validProviders = $isAdvertising
            ? SmsSenderNumberEnum::advertising()
            : SmsSenderNumberEnum::product();

        if (
            $currentProvider
            && in_array($currentProvider, $validProviders, true)
            && !$this->hasTooManyErrors($currentProvider)
        ) {
            return new ($currentProvider->value)();
        }

        foreach ($validProviders as $providerEnum) {
            if (!$this->hasTooManyErrors($providerEnum)) {
                return new ($providerEnum->value)();
            }
        }

        if (!$isAdvertising) {
            $fallback = SmsSenderNumberEnum::fallback();
            return new ($fallback->value)();
        }

        throw new NotAvailableProviderException();
    }

    private function recordProviderError(string $providerClassName): void
    {
        $key = CacheKeys::smsProviderError($providerClassName);
        Cache::add($key, 0, now()->addMinutes(self::ERROR_CACHE_DURATION_MINUTES));
        Cache::increment($key);
    }

    private function hasTooManyErrors(SmsSenderNumberEnum $providerEnum): bool
    {
        $key = CacheKeys::smsProviderError($providerEnum->value);
        return Cache::get($key, 0) >= self::MAX_PROVIDER_ERRORS;
    }
}
