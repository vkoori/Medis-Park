<?php

namespace Modules\User\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use libphonenumber\PhoneNumberUtil;
use Modules\User\Support\FormattedPhoneNumber;

class UserAccountAccessRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'mobile' => ['required', 'regex:/^09\d{9}$/']
        ];
    }

    protected function passedValidation()
    {
        $this->merge([
            'mobile' => PhoneNumberUtil::getInstance()->parse($this->mobile, 'IR'),
        ]);
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();

        $data['mobile'] = new FormattedPhoneNumber(
            phoneNumber: PhoneNumberUtil::getInstance()->parse($data['mobile'], 'IR')
        );

        return is_null($key) ? $data : ($data[$key] ?? $default);
    }
}
