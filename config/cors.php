<?php

return [
    'paths' => ['api/*', 'login', 'logout', 'me', 'statistics/*', '*/statistics/*', '*/reports*', '*/sanctions*', '*/weekends*', 'students*', 'student/*', '*/received*', '*/show/*', '*/avis/*', '*/refuse/*', 'notifications/*', '*/notifications/*', 'officers*', 'officers/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'http://localhost:5175',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
