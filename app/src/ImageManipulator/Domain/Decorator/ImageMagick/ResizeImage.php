<?php

namespace App\ImageManipulator\Domain\Decorator\ImageMagick;

use App\ImageManipulator\Domain\Decorator\ResizeImageInterface;
use App\ImageManipulator\Domain\ValueObject\BestFitFlag;
use App\ImageManipulator\Domain\ValueObject\BlurFactor;
use App\ImageManipulator\Domain\ValueObject\Height;
use App\ImageManipulator\Domain\ValueObject\ImageMagick\ImageFilter;
use App\ImageManipulator\Domain\ValueObject\Width;

class ResizeImage extends Decorator implements ResizeImageInterface
{
    private readonly Width $width;
    private readonly Height $height;
    private ?ImageFilter $filter = null;
    private ?BlurFactor $blur = null;
    private ?BestFitFlag $bestFitFlag = null;

    public function __construct(
        protected ImageMagickImageManipulatorInterface $component,
        private readonly array $parameters
    ) {
        parent::__construct($component);

        $this->resolveParameters();
    }

    public function modifyImage(): \Imagick
    {
        parent::modifyImage()
            ->resizeImage($this->width->value(), $this->height->value(), $this->filter->value(), $this->blur->value(), $this->bestFitFlag->value());

        return parent::getImage();
    }

    public static function can(string $modificator, array $parameters): bool
    {
        return 'resize' == $modificator && sizeof($parameters) >= 2;
    }

    private function resolveParameters(): void
    {
        $this->width = Width::toInt($this->parameters[0]);
        $this->height = Height::toInt($this->parameters[1]);

        $this->blur = isset($this->parameters[2]) ? BlurFactor::toFloat($this->parameters[2]) : BlurFactor::default();
        $this->bestFitFlag = isset($this->parameters[3]) ? BestFitFlag::toBool($this->parameters[3]) : BestFitFlag::default();
        $this->filter = isset($this->parameters[4]) ? ImageFilter::toInt($this->parameters[4]) : ImageFilter::default();
    }
}
