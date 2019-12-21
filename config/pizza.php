<?php

return [
    'adminEmail' => env('ADMIN_EMAIL'),
    'generatePasswordAsEmail' => env('GENERATE_PASSWORD_AS_EMAIL', false),
    'nginx' => [
        'listen' => env('NGINX_LISTEN', '80'),
        'nuxt' => env('NGINX_NUXT_PROXY', 'http://localhost:3000'),
        'fpm' => env('NGINX_FPM', 'php-fpm'),
    ],
    'price' => (float)env('USD_RATE', 0.9),
];
