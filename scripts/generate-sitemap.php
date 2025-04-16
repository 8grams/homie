<?php

$rootDir = __DIR__ . '/../';
require_once $rootDir . 'vendor/autoload.php';
require_once $rootDir . 'src/dependencies/rb-sqlite.php';

use Symfony\Component\Dotenv\Dotenv;

$envPath = $rootDir . '.env';
if (!file_exists($envPath)) {
    exit;    
}

$dotenv = new Dotenv();
$dotenv->usePutenv()->loadEnv($envPath, overrideExistingVars: true);

if (getenv('RUN_ON_CLI') == 'true') {
    $config = require $rootDir . 'src/dependencies/config.php';
    $container = include $rootDir . 'src/dependencies/injector.php';
    
    $sitemapGenerator = $container->get('sitemap_generator');
    $sitemapGenerator->setUrl($config['app_url']);

    $maxTagSetting = $config['sitemap']['max_tags_per_sitemap'];
    $maxTags = $maxTagSetting == 0 ? false : $maxTagSetting;
    $sitemapGenerator->maxTagsPerSitemap($maxTags);
    $sitemapGenerator->writeToFile($rootDir . 'public/sitemap.xml', $container->get('template'), $config);

    echo("Sitemap generated successfully.\n");
}