<?php

use DI\Container;
use Slim\Factory\AppFactory;

session_start();

require __DIR__ . '/../vendor/autoload.php';

try {
	$dotenv = (new \Dotenv\Dotenv(__DIR__ . '/../'))->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
	//
}

$container = new Container();
// Set container to create App with on AppFactory
AppFactory::setContainer($container);

$app = AppFactory::create();
$responseFactory = $app->getResponseFactory();

$routeCollector = $app->getRouteCollector();

$routeParser = $app->getRouteCollector()->getRouteParser();

$container->set('settings', function () {
    return [
    	'displayErrorDetails' => true,
        'app' => [
            'name' => getenv('APP_NAME')
        ]
    ];
});

require_once __DIR__ . '/database.php';

$container->set('router', function () use ($routeParser) {
    return $routeParser;
});

$container->set('db', function () use ($capsule) {
	return $capsule;
});

$container->set('auth', function() {
	return new \App\Auth\Auth;
});

$app->addBodyParsingMiddleware();

require __DIR__ . '/../app/routes.php';
