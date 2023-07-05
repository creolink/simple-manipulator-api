<?php

namespace App\ImageManipulator\Infrastructure\Http\Controller;

use App\ImageManipulator\Application\ImageModificationCommand;
use App\ImageManipulator\Domain\Event\ModifiedImageCreated;
use App\ImageManipulator\Domain\ModifiedImage;
use App\ImageManipulator\Domain\Shared\Bus\Command\CommandBusInterface;
use App\ImageManipulator\Domain\Shared\Bus\Event\EventbusInterface;

class ModifyImageController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
        private readonly EventbusInterface $eventBus
    ) {
    }

    private function executeModifications(array $requestParameters): void
    {
        $imageName = array_shift($requestParameters);
        $modificators = $requestParameters;

        $command = new ImageModificationCommand(
            $imageName,
            $modificators
        );

        $this->commandBus->dispatch($command);
    }

    private function getModifiedImageName(): string
    {
        $event = $this->eventBus->listenTo(ModifiedImageCreated::class);

        /** @var ModifiedImage $modifiedImage */
        $modifiedImage = $event->getPayload();

        return $modifiedImage->getImageName();
    }

    public function __invoke(array $requestParameters): array
    {
        $this->executeModifications($requestParameters);

        $modifiedImageName = $this->getModifiedImageName();

        $response['status_code'] = 'HTTP/1.1 303 See Other';
        $response['location'] = 'Location: /'.$modifiedImageName;

        return $response;
    }
}
