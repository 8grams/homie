<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

// installation
$routes->add('init', new Route('/init', [
    '_controller' => 'App\Libs\InitController::init',
]));

$routes->add('migrate', new Route('/migrate', [
    '_controller' => 'App\Libs\InitController::migrate',
]));

// admin routes
$routes->add('admin_pages', new Route('/admin/{path}', [
    '_controller' => 'App\Libs\AdminController::render',
], ['path' => '.*']));

// primary routes
// using locale
$routes->add('catch_all_with_locale', new Route('/{locale}/{path}', [
    '_controller' => 'App\Libs\Controller::render',
    'locale' => 'id',
], ['path' => '.*', 'locale' => '[a-z]{2}']));

// not using locale
$routes->add('catch_all_with_no_locale', new Route('/{path}', [
    '_controller' => 'App\Libs\Controller::render',
], ['path' => '.*']));

// custom routes will be placed here

return $routes;