<?php

namespace App\ImageManipulator\Domain\ValueObject;

use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;
use App\ImageManipulator\Domain\ValueObject\Shared\ValueObjectInterface;

class DecoratorRegistry implements ValueObjectInterface
{
    public function __construct(
        private readonly ImageManipulatorInterface $imageManipulator,
        private readonly array $registryItems
    ) {
    }

    public function getImageManipulator(): ImageManipulatorInterface
    {
        return $this->imageManipulator;
    }

    public function getRegistryItems(): array
    {
        return $this->registryItems;
    }
}
