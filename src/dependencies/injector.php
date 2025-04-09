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
use App\Libs\Mailer;
use App\Libs\Sitemap\SitemapGenerator;
use App\Libs\Writer;
use Spatie\Crawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mailer\Mailer as MailerMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Transport\TransportInterface;

$container = new ContainerBuilder();

$container->register('context', RequestContext::class);

if (isset($routes)) $container->register('matcher', UrlMatcher::class)->setArguments([$routes, new Reference('context')]);
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

if ($config['blog']['engine'] == 'wordpress') {
    $container->register('blog', Wordpress::class)
        ->setArguments([
            'config' => $config,
            'cache' => $container->get('cache'),
            'client' => $container->get('httpClient')
        ]);
} else if ($config['blog']['engine'] == 'writer') {
    $container->register('blog', Writer::class)
        ->setArguments([
            'config' => $config,
            'cache' => $container->get('cache'),
            'db' => $container->get('db')
        ]);
}

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

if (isset($_SERVER['RUN_ON_CLI']) && $_SERVER['RUN_ON_CLI'] == 'true') {
    $container->register('crawler', Crawler::class)
        ->setFactory([Crawler::class, 'create']);

    $container->register('sitemap_generator', SitemapGenerator::class)
        ->setArguments([
            $container->get('crawler'),
            $config['sitemap']
        ]);
}

// Mailer configuration
$container->register('mailer.transport', TransportInterface::class)
    ->setFactory([Transport::class, 'fromDsn'])
    ->setArguments([
        $config['mailer']['dsn']
    ]);

$container->register('mailer.send', MailerMailer::class)
    ->setArguments([
        $container->get('mailer.transport')
    ]);

$container->register('mailer', Mailer::class)
    ->setArguments([
        $container->get('mailer.send'),
        $container->get('template'),
        $config
    ]);

return $container;