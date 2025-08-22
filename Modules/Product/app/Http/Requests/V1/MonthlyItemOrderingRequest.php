<?php

namespace Modules\Product\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyItemOrderingRequest extends FormRequest
{
    public function rules()
    {
        return [
            'item_id' => ['required', 'integer'],
            'type' => ['required', 'in:component,coin'],
        ];
    }
}
