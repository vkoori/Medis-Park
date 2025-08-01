<?php

namespace Modules\User\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class CheckOtpRequest extends FormRequest
{
    public function prepareForValidation()
    {
        $userAgent = $this->header(key: 'User-Agent', default: '');

        $issuer = 'unknown';
        if (
            str_contains(haystack: $userAgent, needle: 'Android')
        ) {
            $issuer = 'android';
        } elseif (
            str_contains(haystack: $userAgent, needle: 'iPhone')
            || str_contains(haystack: $userAgent, needle: 'iOS')
        ) {
            $issuer = 'ios';
        } elseif (
            str_contains(haystack: $userAgent, needle: 'Mozilla')
        ) {
            $issuer = 'web';
        }

        $this->merge([
            'issuer' => $issuer
        ]);
    }

    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'otp' => ['required', 'string'],
            'issuer' => ['required', 'string'],
        ];
    }
}
