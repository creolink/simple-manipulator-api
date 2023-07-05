<?php

namespace App\ImageManipulator\Infrastructure\Persistance;

use App\ImageManipulator\Domain\OriginalImage;
use App\ImageManipulator\Domain\ValueObject\GD\ImageLibIdentifier;
use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageMimeType;
use App\ImageManipulator\Domain\ValueObject\ImageName;
use App\ImageManipulator\Infrastructure\Persistance\GDStrategies\GDStrategiesInterface;

class GDFileRepository extends FileRepository
{
    private readonly array $strategies;

    public function __construct(array $DGStrategies)
    {
        $this->strategies = $DGStrategies;
    }

    public function getOriginalImage(ImageName $imageName): OriginalImage
    {
        if (!extension_loaded('gd')) {
            throw new \RuntimeException('PHP GD Library is missing');
        }

        $filePath = self::SRC_IMAGE_FOLDER.$imageName;
        $imageData = getimagesize($filePath);

        $mimeType = ImageMimeType::stringify($imageData['mime']);

        $image = null;

        /** @var GDStrategiesInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->can($mimeType->value())) {
                $image = $strategy->loadFile($imageName);
            }
        }

        if (null == $image) {
            throw new \RuntimeException("GD Library doesn't handle this image type!");
        }

        return new OriginalImage(
            $imageName,
            ImageLibIdentifier::create($image),
            $mimeType,
            []
        );
    }

    public function saveModifiedImage(ImageLibIdentifierInterface $imageResource, string $imageName, string $mimeType): void
    {
        $imageFileName = self::TMP_IMAGE_FOLDER.$imageName;

        /** @var GDStrategiesInterface $strategy */
        foreach ($this->strategies as $strategy) {
            if ($strategy->can($mimeType)) {
                $strategy->saveFile($imageResource->value(), $imageFileName);
            }
        }

        imagedestroy($imageResource->value());
    }
}
