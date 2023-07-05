<?php

namespace App\ImageManipulator\Domain\Shared\Bus\Event;

interface EventbusInterface
{
    public function publish(DomainEvent ...$events): void;

    public function listenTo(string $event): ?DomainEvent;
}
