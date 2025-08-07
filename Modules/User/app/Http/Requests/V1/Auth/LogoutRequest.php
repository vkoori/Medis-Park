<?php

namespace Modules\User\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Vkoori\JwtAuth\Auth\Traits\JwtParserTrait;

class LogoutRequest extends FormRequest
{
    use JwtParserTrait;

    public function prepareForValidation()
    {
        $access = $this->getAccess(request: $this);
        if (str_starts_with($access, 'Bearer ')) {
            $access = substr($access, 7);
        }

        $this->merge(input: [
            'access' => $access
        ]);
    }

    public function rules(): array
    {
        return [
            'access' => ['required', 'string']
        ];
    }
}
