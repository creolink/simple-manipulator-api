<?php

use App\ImageManipulator\Domain\Decorator\ImageMagick\CropImage;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ImageManipulator;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ResizeImage;
use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;
use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Infrastructure\Persistance\ImageMagickFileRepository;

use function DI\autowire;

// For ImageMagic configuration
return [
    'imageDecorators' => [
        CropImage::class,
        ResizeImage::class,
    ],
    ImageManipulatorInterface::class => autowire(ImageManipulator::class),
    FileRepositoryInterface::class => autowire(ImageMagickFileRepository::class),
];
