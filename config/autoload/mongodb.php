<?php

declare(strict_types=1);

return [
    'host' => getenv('MONGODB_HOST') ?: 'localhost',
    'port' => (int) (getenv('MONGODB_PORT') ?: 27017),
    'username' => getenv('MONGODB_USERNAME') ?: '',
    'password' => getenv('MONGODB_PASSWORD') ?: '',
    'database' => getenv('MONGODB_DATABASE') ?: 'hyperf_db',
    'auth_source' => getenv('MONGODB_AUTH_SOURCE') ?: 'admin',
    'options' => [
        'connectTimeoutMS' => 3000,
        'socketTimeoutMS' => 30000,
        'serverSelectionTimeoutMS' => 5000,
    ],
];
