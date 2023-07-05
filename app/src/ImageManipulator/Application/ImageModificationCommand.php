<?php

namespace App\ImageManipulator\Application;

use App\ImageManipulator\Domain\Shared\Bus\Command\Command;
use App\ImageManipulator\Domain\Shared\Bus\Command\CommandInterface;

class ImageModificationCommand extends Command implements CommandInterface
{
    public function __construct(
        private readonly string $imageName,
        private readonly array $modificators
    ) {
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }

    public function getModificators(): array
    {
        return $this->modificators;
    }
}
