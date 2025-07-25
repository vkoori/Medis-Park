<?php

return [
    'kavenegar' => [
        'apiKey' => env('KAVENEGAR_API_KEY', '/'),
        'baseUrl' => rtrim(env('KAVENEGAR_BASE_URL', '/')),
        'sender' => null,
    ],
];
