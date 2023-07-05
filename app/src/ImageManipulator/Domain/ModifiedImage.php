<?php

namespace App\ImageManipulator\Domain;

use App\ImageManipulator\Domain\Event\ModifiedImageCreated;
use App\ImageManipulator\Domain\Shared\AggregateRoot;
use App\ImageManipulator\Domain\ValueObject\DecoratorRegistry;
use App\ImageManipulator\Domain\ValueObject\DecoratorsRegistryItem;
use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageMimeType;
use App\ImageManipulator\Domain\ValueObject\ImageName;

class ModifiedImage extends AggregateRoot
{
    private readonly ImageName $imageName;
    private readonly ImageMimeType $mimeType;
    private ImageLibIdentifierInterface $imageResource;

    public function __construct(
        private readonly OriginalImage $originalImage,
        private readonly DecoratorRegistry $decoratorsRegistry
    ) {
        $this->imageResource = $originalImage->getImageResource();
        $this->imageName = $this->generateName();
        $this->mimeType = $originalImage->getImageMimeType();
    }

    public function getImageName(): ImageName
    {
        return $this->imageName;
    }

    public function getImageResource(): ImageLibIdentifierInterface
    {
        return $this->imageResource;
    }

    public function getMimeType(): ImageMimeType
    {
        return $this->mimeType;
    }

    public function applyModificators(): ImageLibIdentifierInterface
    {
        $manipulator = $this->decoratorsRegistry->getImageManipulator();
        $manipulator->setImageLibIdentifier($this->imageResource->value());

        /** @var DecoratorsRegistryItem $decoratorsRegistryItem */
        foreach ($this->decoratorsRegistry->getRegistryItems() as $decoratorsRegistryItem) {
            $class = $decoratorsRegistryItem->getModificatorClass();
            $manipulator = new $class($manipulator, $decoratorsRegistryItem->getParams());
        }

        $result = $manipulator->modifyImage();
        $class = $this->imageResource::class;

        return $class::create($result);
    }

    public function notifyImageCreated(): void
    {
        $this->recordEvent(new ModifiedImageCreated($this));
    }

    private function generateName(): ImageName
    {
        $imageName = $this->originalImage->getImageName()->value();

        $modificationName = function ($registryItems) {
            $result = '';

            /** @var DecoratorsRegistryItem $decoratorsRegistryItem */
            foreach ($registryItems as $decoratorsRegistryItem) {
                $result .= $decoratorsRegistryItem->getModificator().implode('', $decoratorsRegistryItem->getParams());
            }

            return md5($result);
        };

        return ImageName::stringify(
            sprintf('%s.%s', $modificationName($this->decoratorsRegistry->getRegistryItems()), $imageName)
        );
    }
}
