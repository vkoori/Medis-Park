<?php

namespace Modules\Order\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class OrderFilterRequest extends FormRequest
{
    public function prepareForValidation()
    {
        if ($this->filled('unused')) {
            $this->merge([
                'unused' => filter_var($this->get('unused'), FILTER_VALIDATE_BOOLEAN)
            ]);
        }
    }

    public function rules()
    {
        return [
            'mobile' => ['nullable', 'regex:/^09\d{9}$/'],
            'unused' => ['nullable', 'bool']
        ];
    }

    protected function passedValidation()
    {
        if ($this->filled('mobile')) {
            $mobile = $this->get('mobile');

            if (preg_match('/^09\d{9}$/', $mobile)) {
                $mobile = '+98' . substr($mobile, 1);
            }

            $this->merge([
                'mobile' => $mobile,
            ]);
        }
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated();

        if (preg_match('/^09\d{9}$/', $data['mobile'] ?? '')) {
            $data['mobile'] = '+98' . substr($data['mobile'], 1);
        }

        return is_null($key) ? $data : ($data[$key] ?? $default);
    }
}
