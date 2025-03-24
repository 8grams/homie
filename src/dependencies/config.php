<?php

use App\Libs\Sitemap\Crawler\Profile;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\RequestOptions;

return [
    'app_url' => $_ENV['APP_URL'],
    'blog' => [
        'enabled' => $_ENV['ENABLE_BLOG'] == 'true',
        'username' => $_ENV['BLOG_API_USERNAME'],
        'password' => $_ENV['BLOG_API_PASSWORD'],
        'url' => $_ENV['BLOG_API_URL'],
        'site_url' => $_ENV['BLOG_SITE_URL'],
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
    ],
    'sitemap' => [
        'guzzle_options' => [
            RequestOptions::COOKIES => true,
            RequestOptions::CONNECT_TIMEOUT => 300,
            RequestOptions::READ_TIMEOUT => 300,
            RequestOptions::TIMEOUT => 300,
            RequestOptions::ALLOW_REDIRECTS => false,
            RequestOptions::VERIFY => false,
        ],
        'execute_javascript' => false,
        'chrome_binary_path' => null,
        'crawl_profile' => Profile::class,
    ]
    
];