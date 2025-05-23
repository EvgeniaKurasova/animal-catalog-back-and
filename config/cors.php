<?php

return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],  // Дозволяємо всі методи
    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:3000')],
    'allowed_headers' => ['*'],  // Дозволяємо всі заголовки
    'supports_credentials' => true,
]; 