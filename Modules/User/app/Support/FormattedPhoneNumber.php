<?php

namespace Modules\User\Support;

use libphonenumber\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use Stringable;

class FormattedPhoneNumber implements Stringable
{
    private PhoneNumber $phoneNumber;
    private PhoneNumberUtil $phoneUtil;

    public function __construct(PhoneNumber $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    public function __toString(): string
    {
        return $this->phoneUtil->format($this->phoneNumber, PhoneNumberFormat::E164);
    }

    public function getCountryCode(): int
    {
        return $this->phoneNumber->getCountryCode();
    }

    public function getNationalNumber(): ?string
    {
        return $this->phoneNumber->getNationalNumber();
    }

    public function formatNational(): string
    {
        return $this->phoneUtil->format($this->phoneNumber, PhoneNumberFormat::NATIONAL);
    }

    public function formatInternational(): string
    {
        return $this->phoneUtil->format($this->phoneNumber, PhoneNumberFormat::INTERNATIONAL);
    }

    public function toRawPhoneNumber(): PhoneNumber
    {
        return $this->phoneNumber;
    }
}
