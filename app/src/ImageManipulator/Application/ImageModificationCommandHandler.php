<?php

namespace App\ImageManipulator\Application;

use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Domain\ModificationParameterResolver;
use App\ImageManipulator\Domain\ModifiedImage;
use App\ImageManipulator\Domain\Shared\Bus\Command\CommandHandlerInterface;
use App\ImageManipulator\Domain\Shared\Bus\Event\EventbusInterface;
use App\ImageManipulator\Domain\ValueObject\ImageName;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ImageModificationCommandHandler implements CommandHandlerInterface, EventSubscriberInterface
{
    public function __construct(
        private readonly FileRepositoryInterface $repository,
        private readonly EventbusInterface $eventbus,
        private readonly ModificationParameterResolver $modificationParameterResolver
    ) {
    }

    public static function getSubscribedEvents()
    {
        return [
            ImageModificationCommand::class => '__invoke',
        ];
    }

    public function __invoke(ImageModificationCommand $command)
    {
        $imageName = ImageName::stringify($command->getImageName());

        $this->repository->locateOriginalImage($imageName->value());

        $modifiedImage = new ModifiedImage(
            $this->repository->getOriginalImage($imageName),
            $this->modificationParameterResolver->resolveModificators($command->getModificators())
        );

        $this->repository->saveModifiedImage(
            $modifiedImage->applyModificators(),
            $modifiedImage->getImageName(),
            $modifiedImage->getMimeType()
        );

        $modifiedImage->notifyImageCreated();

        $this->eventbus->publish(...$modifiedImage->fetchEvents());
    }
}
