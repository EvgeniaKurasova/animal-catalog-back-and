<?php

return [
    // 'paths' => ['api/*', 'sanctum/csrf-cookie'], ось
    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout'],
    'allowed_methods' => ['*'],  // Дозволяємо всі методи
    // 'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
    // 'allowed_origins' => ['*'],
    // 'allowed_origins' => [env('FRONTEND_URL')],
    'allowed_origins' => ['http://localhost:3000'],
    // 'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],  // Дозволяємо всі заголовки
    // 'exposed_headers' => [],
    // 'max_age' => 0,
    'supports_credentials' => true,
];