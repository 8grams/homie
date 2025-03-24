<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/dependencies/rb-sqlite.php';

use Symfony\Component\Dotenv\Dotenv;

$envPath = __DIR__.'/.env';
if (!file_exists($envPath)) {
    exit;    
}

$dotenv = new Dotenv();
$dotenv->loadEnv($envPath, overrideExistingVars: true);

if ($_SERVER['RUN_ON_CLI'] == 'true') {
    $config = require __DIR__.'/src/dependencies/config.php';
    $container = include __DIR__ . '/src/dependencies/injector.php';
    
    $sitemapGenerator = $container->get('sitemap_generator');
    $sitemapGenerator->setUrl($config['app_url']);
    $sitemapGenerator->writeToFile(__DIR__ . '/public/sitemap.xml', $container->get('template'));
}