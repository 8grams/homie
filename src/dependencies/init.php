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

// copy .env.example to .env
if (!file_exists(__DIR__.'/../../.env') && file_exists(__DIR__.'/../../.env.example')) {
    copy(__DIR__.'/../../.env.example', __DIR__.'/../../.env');
}

Ignition::make()
    ->setTheme('dark')
    ->shouldDisplayException($config['app']['debug'])
    ->register();