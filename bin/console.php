<?php

declare(strict_types=1);

use App\Commands\NotifyLeftCartsCommand;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Nette\DI\Extensions\SearchExtension;
use Symfony\Component\Console\Application;

require __DIR__ . '/../vendor/autoload.php';

const CACHE_DIR = __DIR__ . '/../temp/cache';

$loader = new ContainerLoader(CACHE_DIR);

$class = $loader->load(function (Compiler $compiler) {
    $compiler->addExtension('search', new SearchExtension(CACHE_DIR));
    $compiler->loadConfig(__DIR__ . '/../config/config.neon');
});

/** @var Container $container */
$container = new $class();

$application = new Application();

$application->add($container->getByType(NotifyLeftCartsCommand::class));

$application->run();
