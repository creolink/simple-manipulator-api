<?php

namespace App\ImageManipulator\Domain\Decorator;

interface ImageDecoratorInterface
{
    public static function can(string $modificator, array $parameters): bool;
}
