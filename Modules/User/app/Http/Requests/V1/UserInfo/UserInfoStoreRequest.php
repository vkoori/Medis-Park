<?php

namespace Modules\User\Http\Requests\V1\UserInfo;

use App\Rules\SafePersianWordRule;
use Illuminate\Foundation\Http\FormRequest;
use Modules\User\Rules\NationalCodeRule;
use Vkoori\JwtAuth\Auth\Traits\JwtParserTrait;

class UserInfoStoreRequest extends FormRequest
{
    use JwtParserTrait;

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

        $this->merge(input: [
            'issuer' => $issuer,
            'access_token' => $this->getAccess(request: $this)
        ]);
    }

    public function rules()
    {
        return [
            "national_code" => ['nullable', new NationalCodeRule()],
            "email" => ['nullable', 'email:rfc'],
            "first_name" => ['required', new SafePersianWordRule(), 'max:255'],
            "last_name" => ['required', new SafePersianWordRule(), 'max:255'],
            "address" => ['nullable', new SafePersianWordRule(), 'max:500'],
            // "address_label" => ['nullable', new SafePersianWordRule(), 'max:100'],
            // "address_postal_code" => ['nullable', 'digits:10'],
            // "address_lat" => ['nullable', 'numeric', 'between:-90,90'],
            // "address_long" => ['nullable', 'numeric', 'between:-180,180'],
            "access_token" => ['required', 'string'],
            "issuer" => ['required', 'string'],
        ];
    }
}
