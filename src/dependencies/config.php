<?php

return [
    'blog' => [
        'username' => $_ENV['BLOG_API_USERNAME'],
        'password' => $_ENV['BLOG_API_PASSWORD'],
        'url' => $_ENV['BLOG_API_URL'],
        'enable_cache' => $_ENV['BLOG_API_ENABLE_CACHE'] == 'true',
    ],
    'database' => [
        'path' => __DIR__.'/../../data/' . $_ENV['SQLITE_DATABASE'],
    ],
    'template' => [
        'path' => __DIR__.'/../pages',
    ],
    'admin_template' => [
        'path' => __DIR__.'/../admin',
    ],
    'admin' => [
        'username' => $_ENV['ADMIN_USERNAME'],
        'password' => $_ENV['ADMIN_PASSWORD'],
    ],
    'lang' => [
        'default' => $_ENV['DEFAULT_LANG'] ?? 'id',
        'path' => __DIR__.'/../lang',
    ],
    'cache' => [
        'ttl' => $_ENV['CACHE_TTL'] ?? 3600,
    ],
    'app' => [
        'debug' => $_ENV['DEBUG'] == 'true',
    ]
];