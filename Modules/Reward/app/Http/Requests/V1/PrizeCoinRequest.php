<?php

namespace Modules\Reward\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class PrizeCoinRequest extends FormRequest
{
    public function rules()
    {
        return [
            'coin_amount' => ['required', 'integer']
        ];
    }
}
