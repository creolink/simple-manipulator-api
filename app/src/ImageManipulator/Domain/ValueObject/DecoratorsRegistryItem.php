<?php

namespace App\ImageManipulator\Domain\ValueObject;

use App\ImageManipulator\Domain\ValueObject\Shared\ValueObjectInterface;

class DecoratorsRegistryItem implements ValueObjectInterface
{
    public function __construct(
        private readonly string $modificatorClass,
        private array $params,
        private string $modificator
    ) {
    }

    public function getModificatorClass(): string
    {
        return $this->modificatorClass;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getModificator(): string
    {
        return $this->modificator;
    }
}
