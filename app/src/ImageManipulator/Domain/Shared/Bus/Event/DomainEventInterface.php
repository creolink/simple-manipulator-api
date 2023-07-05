<?php

namespace App\ImageManipulator\Domain\Shared\Bus\Event;

use App\ImageManipulator\Domain\Shared\AggregateRoot;

interface DomainEventInterface
{
    public function getPayload(): AggregateRoot;
}
