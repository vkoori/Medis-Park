<?php

namespace Modules\Notification\Traits;

use App\Exceptions\NotImplementedException;
use Modules\Notification\Dto\SmsableDto;
use Modules\Notification\Enums\SmsSenderNumberEnum;
use Modules\Notification\Enums\SmsTemplateEnum;
use Modules\Notification\Models\UnknownUserDto;
use Modules\Notification\Repositories\SmsTemplateRepository;
use Modules\User\Models\User;
use Modules\User\Support\FormattedPhoneNumber;

trait SmsNotificationTrait
{
    private SmsTemplateEnum $smsTemplate;
    private ?SmsSenderNumberEnum $sender = null;
    private bool $advertising = false;

    public function toSms($notifiable): SmsableDto
    {
        $content = $this->getMessage($notifiable);
        $receptor = $this->getReceptorMobile($notifiable);

        return (new SmsableDto())
            ->setSender(sender: $this->sender)
            ->setReceptor(receptor: $receptor)
            ->setMessage(message: $content)
            ->setAdvertising(advertising: $this->advertising);
    }

    protected function setSmsTemplate(SmsTemplateEnum $template): static
    {
        $this->smsTemplate = $template;

        return $this;
    }

    protected function setSmsSender(SmsSenderNumberEnum $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    protected function advertisingMessage(bool $advertising): static
    {
        $this->advertising = $advertising;

        return $this;
    }

    protected function getMessage($notifiable): string
    {
        $string = $this->getMessageContentRaw();
        $placeholders = $this->toArray($notifiable);

        foreach ($placeholders as $key => $value) {
            $string = str_replace(":$key", $value, $string);
        }

        return $string;
    }

    protected function getReceptorMobile($notifiable): FormattedPhoneNumber
    {
        return match (true) {
            $notifiable instanceof User => $notifiable->mobile,
            $notifiable instanceof UnknownUserDto => $notifiable->mobile,
            default => throw new NotImplementedException(),
        };
    }

    private function getMessageContentRaw(): string
    {
        /** @var SmsTemplateRepository $templateRepository */
        $templateRepository = app(SmsTemplateRepository::class);

        return $templateRepository->getMessage(template: $this->smsTemplate)->content;
    }
}
