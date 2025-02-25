<?php
use Symfony\Component\Dotenv\Dotenv;

return [
    'blog' => [
        'key' => env('BLOG_API_KEY'),
        'url' => env('BLOG_API_URL'),
    ],
    'database' => [
        'path' => env('SQLITE_DB_PATH', __DIR__ . '/../../data/homie.sqlite'),
    ],
];