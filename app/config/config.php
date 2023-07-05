<?php

use App\ImageManipulator\Application\ImageDownloadQueryHandler;
use App\ImageManipulator\Application\ImageModificationCommandHandler;
use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;
use App\ImageManipulator\Domain\ModificationParameterResolver;
use App\ImageManipulator\Domain\Shared\Bus\Command\CommandBusInterface;
use App\ImageManipulator\Domain\Shared\Bus\Event\EventbusInterface;
use App\ImageManipulator\Domain\Shared\Bus\Query\QueryBusInterface;
use App\ImageManipulator\Infrastructure\Bus\InternalInMemoryCommandBus;
use App\ImageManipulator\Infrastructure\Bus\InternalInMemoryEventBus;
use App\ImageManipulator\Infrastructure\Bus\InternalInMemoryQueryBus;
use App\ImageManipulator\Infrastructure\Http\Controller\DisplayImageController;
use App\ImageManipulator\Infrastructure\Http\Controller\ModifyImageController;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use function DI\autowire;
use function DI\create;
use function DI\get;

$libraryConfig = require 'imagemagick.config.php';
// $libraryConfig = require 'gd.config.php';

$generalConfig = [
    CommandBusInterface::class => autowire(InternalInMemoryCommandBus::class),
    ModifyImageController::class => autowire(ModifyImageController::class),
    DisplayImageController::class => autowire(DisplayImageController::class),
    ImageModificationCommandHandler::class => autowire(ImageModificationCommandHandler::class),
    ImageDownloadQueryHandler::class => autowire(ImageDownloadQueryHandler::class),
    EventbusInterface::class => autowire(InternalInMemoryEventBus::class),

    ModificationParameterResolver::class => create(ModificationParameterResolver::class)->constructor(
        get('imageDecorators'),
        get(ImageManipulatorInterface::class)
    ),

    QueryBusInterface::class => autowire(InternalInMemoryQueryBus::class)->constructor([
        get(ImageDownloadQueryHandler::class),
    ]),

    EventDispatcherInterface::class => function ($container) {
        $ed = $container->get(EventDispatcher::class);
        $ed->addSubscriber($container->get(ImageModificationCommandHandler::class));

        return $ed;
    },
];

return $generalConfig + $libraryConfig;
