<?php

declare(strict_types=1);

use App\Handlers\HttpErrorHandler;
use App\Middleware\IdentityMiddleware;
use App\Routes\RoutesBuilder;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\Extensions\SearchExtension;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

const CACHE_DIR = __DIR__ . '/../temp/cache';

$loader = new ContainerLoader(CACHE_DIR);

$class = $loader->load(function (Compiler $compiler) {
    $compiler->addExtension('search', new SearchExtension(CACHE_DIR));
    $compiler->loadConfig(__DIR__ . '/../config/config.neon');
});

/** @var Container $container */
$container = new $class();

$app = AppFactory::create();

$callableResolver = $app->getCallableResolver();
$responseFactory = $app->getResponseFactory();

$errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);

$app->add($container->getByType(IdentityMiddleware::class));

/** @var RoutesBuilder $routeBuilder */
$routeBuilder = $container->getService('routesBuilder');
$routeBuilder->build($app);

$errorMiddleware = $app->addErrorMiddleware(true, false, false);
$errorMiddleware->setDefaultErrorHandler($errorHandler);

return $app;
