<?php

namespace App\ImageManipulator\Infrastructure\Bus;

use App\ImageManipulator\Domain\Shared\Bus\Command\CommandBusInterface;
use App\ImageManipulator\Domain\Shared\Bus\Command\CommandInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class InternalInMemoryCommandBus implements CommandBusInterface
{
    public function __construct(private EventDispatcherInterface $eventDispatcher)
    {
    }

    public function dispatch(CommandInterface ...$commands): void
    {
        foreach ($commands as $event) {
            $this->eventDispatcher->dispatch($event);
        }
    }
}
