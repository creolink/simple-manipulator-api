<?php

namespace Test\ImageManipulator\Application;

use App\ImageManipulator\Application\ImageModificationCommand;
use App\ImageManipulator\Application\ImageModificationCommandHandler;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ImageMagickImageManipulatorInterface;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ImageManipulator;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ResizeImage;
use App\ImageManipulator\Domain\Event\ModifiedImageCreated;
use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Domain\ModificationParameterResolver;
use App\ImageManipulator\Domain\ModifiedImage;
use App\ImageManipulator\Domain\OriginalImage;
use App\ImageManipulator\Domain\Shared\AggregateRoot;
use App\ImageManipulator\Domain\Shared\Bus\Event\EventbusInterface;
use App\ImageManipulator\Domain\ValueObject\DecoratorRegistry;
use App\ImageManipulator\Domain\ValueObject\DecoratorsRegistryItem;
use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageMimeType;
use App\ImageManipulator\Domain\ValueObject\ImageName;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ImageModificationCommandHandler
 *
 * @runTestsInSeparateProcesses
 *
 * @preserveGlobalState disabled
 */
class ImageModificationCommandHandlerTest extends TestCase
{
    private const IMAGE_NAME = 'Foo';
    private const IMAGE_MIME = 'image/gif';
    private const REQUISTED_MODIFICATORS = [];

    public function tearDown(): void
    {
        parent::tearDown();

        m::close();
    }

    public function testImageIsCorrectlyModifiedAndEventIsPublished(): void
    {
        $command = new ImageModificationCommand(self::IMAGE_NAME, self::REQUISTED_MODIFICATORS);

        $imageMagicImageManipulator = m::mock(ImageMagickImageManipulatorInterface::class);
        $imageMagicImageManipulator->shouldReceive('resizeImage');

        $originalImageName = ImageName::stringify($command->getImageName());
        $modifiedImageName = ImageName::stringify(md5($command->getImageName()));
        $imageMime = ImageMimeType::stringify(self::IMAGE_MIME);

        $imageResource = m::mock(ImageLibIdentifierInterface::class);
        $originalImage = new OriginalImage($originalImageName, $imageResource, $imageMime);

        $resolvedModificators = [new DecoratorsRegistryItem(ResizeImage::class, [], 'resize')];
        $decoratorRegistry = new DecoratorRegistry(new ImageManipulator(), $resolvedModificators);

        $modifiedImage = m::mock('overload:'.ModifiedImage::class, AggregateRoot::class)
            ->shouldReceive('__construct')->once()->with(
                $originalImage, $decoratorRegistry
            )
            ->shouldReceive('applyModificators')->once()->andReturn($imageResource)
            ->shouldReceive('getImageName')->twice()->andReturn($modifiedImageName)
            ->shouldReceive('getMimeType')->twice()->andReturn($imageMime)
            ->shouldReceive('notifyImageCreated')->once()
            ->getMock();

        $event = new ModifiedImageCreated($modifiedImage);
        $modifiedImage->shouldReceive('fetchEvents')->andReturn([$event])->getMock();

        $repository = m::mock(FileRepositoryInterface::class)
            ->shouldReceive('locateOriginalImage')->once()->with(self::IMAGE_NAME)
            ->shouldReceive('getOriginalImage')->once()->andReturn($originalImage)
            ->shouldReceive('saveModifiedImage')->once()->with(
                $imageResource,
                $modifiedImageName->value(),
                $imageMime->value()
            );

        $modificationParametersResolver = m::mock(ModificationParameterResolver::class)
            ->shouldReceive('resolveModificators')->once()
            ->with(self::REQUISTED_MODIFICATORS)
            ->andReturns($decoratorRegistry);

        $eventBus = m::mock(EventbusInterface::class)
            ->shouldReceive('publish')->once()->with($event);

        $this->assertEquals($event->getPayload()->getImageName(), md5(self::IMAGE_NAME));
        $this->assertEquals($event->getPayload()->getMimeType(), self::IMAGE_MIME);

        $handler = new ImageModificationCommandHandler(
            $repository->getMock(),
            $eventBus->getMock(),
            $modificationParametersResolver->getMock()
        );

        $handler->__invoke($command);
    }
}
