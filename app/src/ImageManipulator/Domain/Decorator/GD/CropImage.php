<?php

namespace App\ImageManipulator\Domain\Decorator\GD;

use App\ImageManipulator\Domain\Decorator\CropImageInterface;
use App\ImageManipulator\Domain\ValueObject\Height;
use App\ImageManipulator\Domain\ValueObject\StartX;
use App\ImageManipulator\Domain\ValueObject\StartY;
use App\ImageManipulator\Domain\ValueObject\Width;

class CropImage extends Decorator implements CropImageInterface
{
    private readonly Width $width;
    private readonly Height $height;
    private ?StartX $startX = null;
    private ?StartY $startY = null;

    public function __construct(
        protected GDImageManipulatorInterface $component,
        private readonly array $parameters
    ) {
        parent::__construct($component);

        $this->resolveParameters();
    }

    public function modifyImage(): \GdImage
    {
        $currentImage = parent::modifyImage();

        return imagecrop(
            $currentImage,
            [
                'x' => $this->startX->value(),
                'y' => $this->startY->value(),
                'width' => $this->width->value(),
                'height' => $this->height->value(),
            ]
        );
    }

    public static function can(string $modificator, array $parameters): bool
    {
        return 'crop' == $modificator && sizeof($parameters) >= 2;
    }

    private function resolveParameters(): void
    {
        $this->width = Width::toInt($this->parameters[0]);
        $this->height = Height::toInt($this->parameters[1]);
        $this->startX = isset($this->parameters[2]) ? StartX::toInt($this->parameters[2]) : StartX::default();
        $this->startY = isset($this->parameters[3]) ? StartY::toInt($this->parameters[3]) : StartY::default();
    }
}
