<?php

namespace Modules\Product\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class MonthlyComponentSaveRequest extends FormRequest
{
    public function rules()
    {
        return [
            'component_id' => ['required', 'integer'],
            'j_year_month' => ['required', 'regex:/^\d{4}-(0?[1-9]|1[0-2])$/'],
        ];
    }
}
