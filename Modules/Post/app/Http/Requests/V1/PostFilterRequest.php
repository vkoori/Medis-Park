<?php

namespace Modules\Post\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PostFilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['nullable', 'string'],
            'from' => ['nullable', 'required_with:to', 'date_format:Y-m-d\\TH:i:sP'],
            'to' => ['nullable', 'required_with:from', 'date_format:Y-m-d\\TH:i:sP', 'after:from']
        ];
    }
}
