<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class SafePersianWordRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (!is_string($value)) {
            $fail(__(
                'validation.string',
                ['attribute' => $attribute]
            ));
            return;
        }

        if (!preg_match('/^[\p{L}\p{M}\p{N}\s\.\-_,،\(\)\[\]\?!:;\'"\/\\&]+$/u', $value)) {
            $fail(__(
                'validation.persian_words',
                ['attribute' => $attribute]
            ));
        }
    }
}

