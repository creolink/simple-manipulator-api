<?php

namespace Test\ImageManipulator\Domain;

use App\ImageManipulator\Domain\Decorator\CropImageInterface;
use App\ImageManipulator\Domain\Decorator\ImageMagick\CropImage;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ImageManipulator;
use App\ImageManipulator\Domain\Decorator\ImageMagick\ResizeImage;
use App\ImageManipulator\Domain\Decorator\ResizeImageInterface;
use App\ImageManipulator\Domain\Event\ModifiedImageCreated;
use App\ImageManipulator\Domain\ModifiedImage;
use App\ImageManipulator\Domain\OriginalImage;
use App\ImageManipulator\Domain\ValueObject\DecoratorRegistry;
use App\ImageManipulator\Domain\ValueObject\DecoratorsRegistryItem;
use App\ImageManipulator\Domain\ValueObject\ImageLibIdentifierInterface;
use App\ImageManipulator\Domain\ValueObject\ImageMagick\ImageLibIdentifier;
use App\ImageManipulator\Domain\ValueObject\ImageMimeType;
use App\ImageManipulator\Domain\ValueObject\ImageName;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \ModifiedImage
 */
class ModifiedImageTest extends TestCase
{
    use ProphecyTrait;

    private const ORYGINAL_IMAGE_NAME = 'Foo';
    private const MODIFIED_IMAGE_NAME = '5cf4e947a081dfc1724e432412fcd07c.Foo';
    private const IMAGE_MIME = 'image/gif';
    private const REQUISTED_MODIFICATORS = [
        'resize' => ['width', 'height', 'blur', 'bestFitFlag', 'filter'],
        'crop' => ['width', 'height', 'startX', 'startY'],
    ];

    private OriginalImage|ObjectProphecy $originalImage;
    private DecoratorRegistry|ObjectProphecy $decoratorRegistry;
    private ImageLibIdentifierInterface|ObjectProphecy $imageLibIdentifierInterface;
    private \Imagick|ObjectProphecy $lib;
    private ImageMimeType $imageMime;

    public function setUp(): void
    {
        parent::setUp();

        $originalImageName = ImageName::stringify(self::ORYGINAL_IMAGE_NAME);
        $this->imageMime = ImageMimeType::stringify(self::IMAGE_MIME);

        $resolvedModificators = [
            new DecoratorsRegistryItem(ResizeImageInterface::class, self::REQUISTED_MODIFICATORS['resize'], 'resize'),
            new DecoratorsRegistryItem(CropImageInterface::class, self::REQUISTED_MODIFICATORS['crop'], 'crop'),
        ];

        $this->lib = $this->prophesize(\Imagick::class);
        $this->lib->resizeImage(40, 30, 0, 1, false)->willReturn(true);
        $this->lib->cropImage(20, 10, 0, 0)->willReturn(true);

        $this->imageLibIdentifierInterface = $this->prophesize(ImageLibIdentifierInterface::class);
        $this->imageLibIdentifierInterface->value()->willReturn($this->lib->reveal());

        $this->originalImage = $this->prophesize(OriginalImage::class);
        $this->originalImage->getImageResource()->willReturn($this->imageLibIdentifierInterface->reveal());
        $this->originalImage->getImageName()->willReturn($originalImageName);
        $this->originalImage->getImageMimeType()->willReturn($this->imageMime);

        $this->decoratorRegistry = $this->prophesize(DecoratorRegistry::class);
        $this->decoratorRegistry->getRegistryItems()->willReturn($resolvedModificators);
    }

    public function testNameIsCorrectlyGenerated(): void
    {
        $modifiedImageName = ImageName::stringify(self::MODIFIED_IMAGE_NAME);

        $modifiedImage = new ModifiedImage(
            $this->originalImage->reveal(),
            $this->decoratorRegistry->reveal()
        );

        $this->assertEquals($modifiedImageName, $modifiedImage->getImageName());
    }

    public function testNotificationIsDispatched(): void
    {
        $modifiedImage = new ModifiedImage(
            $this->originalImage->reveal(),
            $this->decoratorRegistry->reveal()
        );

        $modifiedImage->notifyImageCreated();
        $events = $modifiedImage->fetchEvents();

        $this->assertCount(1, $events);
        $this->assertEquals(new ModifiedImageCreated($modifiedImage), $events[0]);
    }

    public function testImageIsReturned(): void
    {
        $modifiedImage = new ModifiedImage(
            $this->originalImage->reveal(),
            $this->decoratorRegistry->reveal()
        );

        $this->assertEquals($this->imageLibIdentifierInterface->reveal(), $modifiedImage->getImageResource());
    }

    public function testImageMimeTypeIsReturned(): void
    {
        $modifiedImage = new ModifiedImage(
            $this->originalImage->reveal(),
            $this->decoratorRegistry->reveal()
        );

        $this->assertEquals($this->imageMime, $modifiedImage->getMimeType());
    }

    public function testImageIsModified(): void
    {
        $imageManipulator = $this->prophesize(ImageManipulator::class);
        $imageManipulator->setImageLibIdentifier($this->lib->reveal())->shouldBeCalled();
        $imageManipulator->modifyImage()->shouldBeCalled()->willReturn($this->lib->reveal());
        $imageManipulator->getImage()->willReturn($this->lib->reveal());

        $resizeDecoratorRegistryItem = $this->prophesize(DecoratorsRegistryItem::class);
        $resizeDecoratorRegistryItem->getModificatorClass()->willReturn(ResizeImage::class)->shouldBeCalledOnce();
        $resizeDecoratorRegistryItem->getParams()->willReturn([40, 30])->shouldBeCalledTimes(2);
        $resizeDecoratorRegistryItem->getModificator()->willReturn('resize')->shouldBeCalledOnce();

        $cropDecoratorRegistryItem = $this->prophesize(DecoratorsRegistryItem::class);
        $cropDecoratorRegistryItem->getModificatorClass()->willReturn(CropImage::class)->shouldBeCalledOnce();
        $cropDecoratorRegistryItem->getParams()->willReturn([20, 10])->shouldBeCalledTimes(2);
        $cropDecoratorRegistryItem->getModificator()->willReturn('crop')->shouldBeCalledOnce();

        $decoratorRegistry = $this->prophesize(DecoratorRegistry::class);
        $decoratorRegistry->getRegistryItems()->willReturn([$resizeDecoratorRegistryItem->reveal(), $cropDecoratorRegistryItem->reveal()]);
        $decoratorRegistry->getImageManipulator()->willReturn($imageManipulator->reveal());

        $originalImageName = ImageName::stringify(self::ORYGINAL_IMAGE_NAME);
        $imageMime = ImageMimeType::stringify(self::IMAGE_MIME);

        $imageLibIdentifier = ImageLibIdentifier::create($this->lib->reveal());
        $originalImage = new OriginalImage($originalImageName, $imageLibIdentifier, $imageMime);

        $modifiedImage = new ModifiedImage(
            $originalImage,
            $decoratorRegistry->reveal()
        );

        $result = $modifiedImage->applyModificators();

        // $this->assertEquals($originalImage->getImageResource(), $result);
    }
}
