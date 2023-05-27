<?php

return [
    'chatgpt' => [
        'api_key' => env('CHATGPT_API_KEY'),
        'base_url' => env('CHATGPT_BASE_URL'),
        'model' => env('CHATGPT_MODEL', 'text-davinci-003'), // gpt-3.5-turbo
    ],
];
