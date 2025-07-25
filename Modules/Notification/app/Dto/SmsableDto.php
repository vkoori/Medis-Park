<?php

namespace Modules\Notification\Dto;

use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Modules\Notification\Enums\SmsSenderNumberEnum;
use Modules\User\Support\FormattedPhoneNumber;

final class SmsableDto
{
    private ?SmsSenderNumberEnum $from = null;
    private string $to;
    private string $content;
    private bool $isAdvertising = false;

    public function setSender(?SmsSenderNumberEnum $sender): self
    {
        $this->from = $sender;

        return $this;
    }

    public function setReceptor(FormattedPhoneNumber $receptor): self
    {
        $this->to = PhoneNumberUtil::getInstance()->format(
            $receptor->toRawPhoneNumber(),
            PhoneNumberFormat::E164
        );

        return $this;
    }

    public function setMessage(string $message): self
    {
        $this->content = $message;

        return $this;
    }

    public function setAdvertising(bool $advertising): self
    {
        $this->isAdvertising = $advertising;

        return $this;
    }

    public function getSender(): ?SmsSenderNumberEnum
    {
        return $this->from;
    }

    public function getReceptor(): string
    {
        return $this->to;
    }

    public function getMessage(): string
    {
        return $this->content;
    }

    public function isAdvertising(): bool
    {
        return $this->isAdvertising;
    }
}
