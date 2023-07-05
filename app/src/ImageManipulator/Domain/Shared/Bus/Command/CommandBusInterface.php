<?php

namespace App\ImageManipulator\Domain\Shared\Bus\Command;

interface CommandBusInterface
{
    public function dispatch(CommandInterface ...$commands): void;
}
