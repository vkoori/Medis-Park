<?php

namespace App\Http\Middlewares;

use Closure;
use Illuminate\Http\Request;

class NormalizeInputMiddleware
{
    protected array $arabicToPersianMap = [
        'ي' => 'ی',
        'ك' => 'ک',
        'ە' => 'ه',
        'ة' => 'ه',
        'ؤ' => 'و',
        'إ' => 'ا',
        'أ' => 'ا',
        'ٱ' => 'ا',
    ];

    protected array $persianArabicDigits = [
        '۰' => '0',
        '۱' => '1',
        '۲' => '2',
        '۳' => '3',
        '۴' => '4',
        '۵' => '5',
        '۶' => '6',
        '۷' => '7',
        '۸' => '8',
        '۹' => '9',
        '٠' => '0',
        '١' => '1',
        '٢' => '2',
        '٣' => '3',
        '٤' => '4',
        '٥' => '5',
        '٦' => '6',
        '٧' => '7',
        '٨' => '8',
        '٩' => '9',
    ];

    public function handle(Request $request, Closure $next)
    {
        $request->merge($this->normalizeArray($request->all()));

        return $next($request);
    }

    protected function normalizeArray(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = $this->normalizeArray($value);
            } elseif (is_string($value)) {
                $data[$key] = $this->normalizeString($value);
            }
        }

        return $data;
    }

    protected function normalizeString(string $value): string
    {
        // Replace Arabic chars to Persian
        $value = strtr($value, $this->arabicToPersianMap);

        // Replace Persian & Arabic digits to English
        $value = strtr($value, $this->persianArabicDigits);

        return $value;
    }
}
