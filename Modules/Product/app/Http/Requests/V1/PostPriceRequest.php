<?php

namespace Modules\Product\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PostPriceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'coin_value' => ['required', 'integer']
        ];
    }
}
