<?php

use Symfony\Component\Dotenv\Dotenv;
use Spatie\Ignition\Ignition;

$rootDir = __DIR__.'/../../';

if (!file_exists($rootDir . '.env') && file_exists($rootDir . '.env.example')) {
    copy($rootDir . '.env.example', $rootDir . '.env');
}

$dotenv = new Dotenv();
$envPath = $rootDir . '.env';
if (file_exists($envPath)) {
    $dotenv->usePutenv()->loadEnv($envPath, overrideExistingVars: true);
}

// load config
$config = require $rootDir.'src/dependencies/config.php';

Ignition::make()
    ->setTheme('dark')
    ->shouldDisplayException($config['app']['debug'])
    ->register();