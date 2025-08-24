<?php

namespace Modules\Product\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class ProductFilterRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => ['nullable', 'string', 'max:100']
        ];
    }
}
