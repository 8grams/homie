<?php

return [
    'blog' => [
        'key' => $_ENV['BLOG_API_KEY'],
        'url' => $_ENV['BLOG_API_URL'],
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
    ]
];