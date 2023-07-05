<?php

namespace App\ImageManipulator\Infrastructure\Persistance\GDStrategies;

interface GDStrategiesInterface
{
    public function loadFile(string $imageName): \GdImage;

    public function saveFile(\GdImage $resource, string $imageFileName): void;

    public function can(string $mimeType): bool;
}
