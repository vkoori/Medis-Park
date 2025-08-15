<?php

namespace Modules\Post\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PostFilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['nullable', 'string'],
            'month' => ['nullable', 'regex:/^\d{4}-(0?[1-9]|1[0-2])$/'],
        ];
    }
}
