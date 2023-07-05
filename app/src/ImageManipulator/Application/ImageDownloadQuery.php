<?php

namespace App\ImageManipulator\Application;

use App\ImageManipulator\Domain\Shared\Bus\Query\QueryInterface;

class ImageDownloadQuery implements QueryInterface
{
    public function __construct(
        private readonly string $imageName
    ) {
    }

    public function getImageName(): string
    {
        return $this->imageName;
    }
}
