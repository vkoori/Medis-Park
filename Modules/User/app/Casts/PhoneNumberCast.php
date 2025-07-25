<?php

namespace Modules\User\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\NumberParseException;

class PhoneNumberCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            return $phoneUtil->parse($value, null);
        } catch (NumberParseException $e) {
            return null;
        }
    }

    public function set(Model $model, string $key, mixed $value, array $attributes)
    {
        $phoneUtil = PhoneNumberUtil::getInstance();

        try {
            $number = is_string($value)
                ? $phoneUtil->parse($value, null)
                : $value;

            return $phoneUtil->format($number, PhoneNumberFormat::E164);
        } catch (NumberParseException $e) {
            return null;
        }
    }
}
