<?php

use App\ImageManipulator\Domain\Decorator\GD\CropImage;
use App\ImageManipulator\Domain\Decorator\GD\ImageManipulator;
use App\ImageManipulator\Domain\Decorator\GD\ResizeImage;
use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;
use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Infrastructure\Persistance\GDFileRepository;
use App\ImageManipulator\Infrastructure\Persistance\GDStrategies\GIFFileHandler;
use App\ImageManipulator\Infrastructure\Persistance\GDStrategies\JPGFileHandler;
use App\ImageManipulator\Infrastructure\Persistance\GDStrategies\PNGFileHandler;
use App\ImageManipulator\Infrastructure\Persistance\GDStrategies\WEBPFileHandler;

use function DI\autowire;
use function DI\get;

// For PHP GD configuration
return [
    'imageDecorators' => [
        CropImage::class,
        ResizeImage::class,
    ],
    ImageManipulatorInterface::class => autowire(ImageManipulator::class),
    FileRepositoryInterface::class => autowire(GDFileRepository::class)->constructor([
        get(GIFFileHandler::class),
        get(JPGFileHandler::class),
        get(PNGFileHandler::class),
        get(WEBPFileHandler::class),
    ]),
];
