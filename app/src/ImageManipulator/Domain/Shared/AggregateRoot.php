<?php

namespace App\ImageManipulator\Domain\Shared;

use App\ImageManipulator\Domain\Shared\Bus\Event\DomainEvent;

class AggregateRoot
{
    private array $events = [];

    protected function recordEvent(DomainEvent $event): void
    {
        if (!isset($this->events)) {
            $this->events = [];
        }

        $this->events[] = $event;
    }

    public function fetchEvents(): array
    {
        return $this->events;
    }
}
