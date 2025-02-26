<?php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use App\Libs\Cache;
use App\Libs\SQLiteDatabase;
use App\Libs\WordpressHttpClient;
use App\Libs\Controller;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;

// load env vars
$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__.'/../../.env', overrideExistingVars: true);

// load config
$config = require __DIR__.'/config.php';

$container = new ContainerBuilder();

$container->register('context', RequestContext::class);
$container->register('matcher', UrlMatcher::class)->setArguments([$routes, new Reference('context')]);
$container->register('cache', Cache::class);
$container->register('db', SQLiteDatabase::class);
$container->register('httpClient', HttpClient::class);
$container->register('wpHttpClient', WordpressHttpClient::class)
    ->setArguments([
        'key' => $config['blog']['key'], 
        'url' => $config['blog']['url'],
        'cache' => $container->get('cache'),
        'client' => $container->get('httpClient')
    ]);

$container->register('controller_resolver', ControllerResolver::class);
$container->register('argument_resolver', ArgumentResolver::class);

return $container;