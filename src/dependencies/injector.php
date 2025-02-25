<?php

use App\Libs\Cache;
use App\Libs\Database;
use App\Libs\WordpressHttpClient;
use Symfony\Component\DependencyInjection\ContainerBuilder;

$container = new ContainerBuilder();
$container->register('cache', Cache::class);
$container->register('db', Database::class);
$container->register('httpClient', WordpressHttpClient::class)
    ->setArguments(['key' => '123', 'url' => 'https://blog.com', 'client' => 'httpClient']);