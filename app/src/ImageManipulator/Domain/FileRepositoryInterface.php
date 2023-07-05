<?php

namespace App\ImageManipulator\Domain;

use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageName;

interface FileRepositoryInterface
{
    public const TMP_IMAGE_FOLDER = '../tmp/';
    public const SRC_IMAGE_FOLDER = 'resources/images/';

    public function locateOriginalImage(string $imageName): void;

    public function locateModifiedImage(string $imageName): void;

    public function saveModifiedImage(ImageLibIdentifierInterface $imageResource, string $imageName, string $mimeType): void;

    public function getModifiedImage(string $imageName): string;

    public function getModifiedImageData(string $imageName): array;

    public function getOriginalImage(ImageName $imageName): OriginalImage;
}
