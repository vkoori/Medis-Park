<?php

namespace Modules\User\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class NationalCodeRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$this->isValidIranianNationalCode($value)) {
            $fail(__(
                'validation.nationalCode',
                ['attribute' => $attribute]
            ));
        }
    }

    private function isValidIranianNationalCode($code)
    {
        if (!preg_match('/^\d{10}$/', $code)) {
            return false;
        }

        $check = (int) $code[9];
        $sum = 0;

        for ($i = 0; $i < 9; $i++) {
            $sum += (int) $code[$i] * (10 - $i);
        }

        $mod = $sum % 11;

        if (($mod < 2 && $check == $mod) || ($mod >= 2 && $check + $mod == 11)) {
            return true;
        }

        return false;
    }
}
