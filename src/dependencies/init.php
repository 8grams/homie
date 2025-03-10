<?php

use Symfony\Component\Dotenv\Dotenv;
use Spatie\Ignition\Ignition;

$dotenv = new Dotenv();
$envPath = __DIR__.'/../../.env';
if (file_exists($envPath)) {
    $dotenv->loadEnv($envPath, overrideExistingVars: true);
}

// load config
$config = require __DIR__.'/config.php';

Ignition::make()
    ->setTheme('dark')
    ->shouldDisplayException($config['app']['debug'])
    ->register();