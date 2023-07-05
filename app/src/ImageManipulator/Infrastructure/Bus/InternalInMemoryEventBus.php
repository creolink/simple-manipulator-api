<?php

namespace App\ImageManipulator\Infrastructure\Bus;

use App\ImageManipulator\Domain\Shared\Bus\Event\DomainEvent;
use App\ImageManipulator\Domain\Shared\Bus\Event\DomainEventInterface;
use App\ImageManipulator\Domain\Shared\Bus\Event\EventbusInterface;

class InternalInMemoryEventBus implements EventbusInterface
{
    private static array $events = [];

    public function publish(DomainEvent ...$events): void
    {
        foreach ($events as $event) {
            self::$events[] = $event;
        }
    }

    public function listenTo(string $event): ?DomainEvent
    {
        // DomainEventInterface
        foreach (self::$events as $dispatchedEvent) {
            $reflectionClass = new \ReflectionClass($dispatchedEvent);

            if ($reflectionClass->getName() == $event) {
                return $dispatchedEvent;
            }
        }

        return null;
    }
}
