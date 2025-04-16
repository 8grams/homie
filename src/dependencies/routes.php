<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

// migrate via web, in case cpanel is only the option and cannot run composer install
$routes->add('init', new Route('/admin/init', [
    '_controller' => 'App\Libs\InitController::init',
]));

$routes->add('migrate', new Route('/admin/migrate', [
    '_controller' => 'App\Libs\InitController::migrate',
]));

$routes->add('refresh', new Route('/admin/refresh', [
    '_controller' => 'App\Libs\InitController::refresh',
]));

// admin routes
$routes->add('admin_pages', new Route('/admin/{path}', [
    '_controller' => 'App\Libs\AdminController::render',
], ['path' => '.*']));

// primary routes
// using locale
$routes->add('catch_all_with_locale', new Route('/{locale}/{path}', [
    '_controller' => 'App\Libs\Controller::render',
    'locale' => $config['lang']['default'],
], ['path' => '.*', 'locale' => '[a-z]{2}']));

// not using locale
$routes->add('catch_all_with_no_locale', new Route('/{path}', [
    '_controller' => 'App\Libs\Controller::render',
], ['path' => '.*']));

// custom routes will be placed here

return $routes;