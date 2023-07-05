<?php

namespace App\ImageManipulator\Domain\Shared\Bus\Event;

use App\ImageManipulator\Domain\Shared\AggregateRoot;

class DomainEvent implements DomainEventInterface
{
    private static int $eventId = 0;

    public function __construct(private readonly AggregateRoot $eventPayload)
    {
        ++self::$eventId;
    }

    public function getPayload(): AggregateRoot
    {
        return $this->eventPayload;
    }
}
