<?php

namespace Modules\User\Http\Requests\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Vkoori\JwtAuth\Auth\Traits\JwtParserTrait;

class RefreshRequest extends FormRequest
{
    use JwtParserTrait;

    public function prepareForValidation()
    {
        $refresh = $this->getRefresh(request: $this);
        if (str_starts_with($refresh, 'Bearer ')) {
            $refresh = substr($refresh, 7);
        }

        $this->merge(input: [
            'refresh' => $refresh
        ]);
    }

    public function rules(): array
    {
        return [
            'refresh' => ['required', 'string']
        ];
    }
}
