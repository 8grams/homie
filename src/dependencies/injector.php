<?php

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use App\Libs\Cache;
use App\Libs\SQLiteDatabase;
use App\Libs\WordpressHttpClient;
use App\Libs\ViewEngine as ViewEngine;
use App\Libs\Auth\UserProvider;
use App\Libs\Auth\Authenticator;


// load env vars

// copy .env.example to .env
if (!file_exists(__DIR__.'/../../.env') && file_exists(__DIR__.'/../../.env.example')) {
    copy(__DIR__.'/../../.env.example', __DIR__.'/../../.env');
}

$dotenv = new Dotenv();
$envPath = __DIR__.'/../../.env';
if (file_exists($envPath)) {
    $dotenv->loadEnv(__DIR__.'/../../.env', overrideExistingVars: true);
}

// load config
$config = require __DIR__.'/config.php';

$container = new ContainerBuilder();

$container->register('context', RequestContext::class);
$container->register('matcher', UrlMatcher::class)->setArguments([$routes, new Reference('context')]);
$container->register('cache', Cache::class);

$container->register('db', SQLiteDatabase::class)
    ->setArguments([
        'connection' => 'sqlite:' . $config['database']['path'], 
    ]);
$container->register('httpClient', HttpClient::class);
$container->register('wpHttpClient', WordpressHttpClient::class)
    ->setArguments([
        'key' => $config['blog']['key'], 
        'url' => $config['blog']['url'],
        'cache' => $container->get('cache'),
        'client' => $container->get('httpClient')
    ]);

$container->register('template', ViewEngine::class)
    ->setArguments([$config['template']['path']]);

$container->register('controller_resolver', ControllerResolver::class);
$container->register('argument_resolver', ArgumentResolver::class);

$container->register('user_provider', UserProvider::class)
    ->setArguments([
        $config['admin']['username'],
        $config['admin']['password']
    ]);
$container->register('authenticator', Authenticator::class)
    ->setArguments([
        $container->get('user_provider')
    ]);

return $container;