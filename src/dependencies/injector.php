<?php

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use App\Libs\Cache;
use App\Libs\SQLiteDatabase;
use App\Libs\Wordpress;
use App\Libs\ViewEngine as ViewEngine;
use App\Libs\Auth\UserProvider;
use App\Libs\Auth\Authenticator;
use Symfony\Component\HttpClient\HttpClient;

// load env vars

// copy .env.example to .env
if (!file_exists(__DIR__.'/../../.env') && file_exists(__DIR__.'/../../.env.example')) {
    copy(__DIR__.'/../../.env.example', __DIR__.'/../../.env');
}

$container = new ContainerBuilder();

$container->register('context', RequestContext::class);
$container->register('matcher', UrlMatcher::class)->setArguments([$routes, new Reference('context')]);
$container->register('cache', Cache::class);

$container->register('db', SQLiteDatabase::class)
    ->setArguments([
        'connection' => 'sqlite:' . $config['database']['path'], 
    ]);
$container->register('cache', Cache::class)
    ->setArguments([
        $container->get('db')
    ]);
$container->register('httpClient', HttpClientInterface::class)
    ->setFactory([HttpClient::class, 'create']);

$container->register('wordpress', Wordpress::class)
    ->setArguments([
        'config' => $config,
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