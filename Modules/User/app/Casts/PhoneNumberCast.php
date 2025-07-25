<?php

namespace Modules\User\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumber;
use Modules\User\Support\FormattedPhoneNumber;

class PhoneNumberCast implements CastsAttributes
{
    protected $phoneUtil;

    public function __construct()
    {
        $this->phoneUtil = PhoneNumberUtil::getInstance();
    }

    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        try {
            $phoneNumberObject = $this->phoneUtil->parse($value, null);
            return new FormattedPhoneNumber($phoneNumberObject);
        } catch (NumberParseException $e) {
            return null;
        }
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        if (is_null($value)) {
            return null;
        }

        try {
            $number = null;
            if ($value instanceof FormattedPhoneNumber) {
                $number = $value->toRawPhoneNumber();
            } elseif ($value instanceof PhoneNumber) {
                $number = $value;
            } elseif (is_string($value)) {
                $number = $this->phoneUtil->parse($value, null);
            }

            if ($number) {
                return $this->phoneUtil->format($number, PhoneNumberFormat::E164);
            }
            return null;

        } catch (NumberParseException $e) {
            return null;
        }
    }
}
