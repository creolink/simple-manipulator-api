<?php

require '../bootstrap.php';

use App\ImageManipulator\Infrastructure\Framework\Router\HttpRouter;
use DI\Container;
use DI\ContainerBuilder;

$container = new Container();
$builder = new ContainerBuilder();
$builder->addDefinitions('../config/config.php');
$container = $builder->build();

$router = new HttpRouter($container);
$router->parseRoute();
$router->createResponse($router->processRequest());
