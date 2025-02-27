<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

$request = Request::createFromGlobals();
$routes = include __DIR__ . '/../src/dependencies/routes.php';
$container = include __DIR__ . '/../src/dependencies/injector.php';

try {
    $request->attributes->add($container->get('matcher')->match($request->getPathInfo()));
    $controller = $container->get('controller_resolver')->getController($request);
    $arguments = $container->get('argument_resolver')->getArguments(
        $request, 
        $controller);

    $controller[0]->setDependencies(
        $request, 
        $container->get('cache'), 
        $container->get('db'), 
        $container->get('wpHttpClient'),
        $container->get('template')
    );
        
    $response = call_user_func_array($controller, $arguments);
} catch (ResourceNotFoundException $exception) {
    $response = new Response('Not Found', 404);
} catch (Exception $exception) {
    $response = new Response('An error occurred', 500);
}

$response->send();