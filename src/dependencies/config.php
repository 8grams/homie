<?php

return [
    'blog' => [
        'key' => getenv('BLOG_API_KEY'),
        'url' => getenv('BLOG_API_URL'),
    ],
    'database' => [
        'path' => getenv('SQLITE_CONNECTION'),
    ],
];