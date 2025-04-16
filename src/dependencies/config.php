<?php

use App\Libs\Sitemap\Crawler\Profile;
use GuzzleHttp\RequestOptions;

return [
    'app_url' => getenv('APP_URL') ?? 'http://localhost:8000',
    'blog' => [
        'enabled' => getenv('ENABLE_BLOG') == 'true',
        'engine' => getenv('BLOG_ENGINE') ?? 'writer',
        'username' => getenv('BLOG_API_USERNAME'),
        'password' => getenv('BLOG_API_PASSWORD'),
        'url' => getenv('BLOG_API_URL'),
        'site_url' => getenv('BLOG_SITE_URL'),
        'enable_cache' => getenv('BLOG_API_ENABLE_CACHE') == 'true',
    ],
    'database' => [
        'path' => __DIR__.'/../../data/' . getenv('SQLITE_DATABASE'),
    ],
    'template' => [
        'path' => __DIR__.'/../pages',
    ],
    'admin_template' => [
        'path' => __DIR__.'/../internal/admin',
    ],
    'email_template' => [
        'path' => __DIR__.'/../internal/emails',
    ],
    'sitemap_template' => [
        'path' => __DIR__.'/../internal/sitemap',
    ],
    'admin' => [
        'username' => getenv('ADMIN_USERNAME'),
        'password' => getenv('ADMIN_PASSWORD'),
    ],
    'lang' => [
        'default' => getenv('DEFAULT_LANG') ?? 'id',
        'path' => __DIR__.'/../lang',
    ],
    'cache' => [
        'ttl' => getenv('CACHE_TTL') ?? 3600,
    ],
    'app' => [
        'debug' => getenv('DEBUG') == 'true',
    ],
    'sitemap' => [
        'site_url' => getenv('APP_URL'),
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
        'max_tags_per_sitemap' => getenv('SITEMAP_MAX_TAGS') ?? 0,
    ],
    'mailer' => [
        'dsn' => getenv('MAILER_DSN'),
    ],
];