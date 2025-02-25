<?php
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$routes = new RouteCollection();

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