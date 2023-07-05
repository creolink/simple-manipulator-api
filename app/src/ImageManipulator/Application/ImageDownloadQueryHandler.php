<?php

namespace App\ImageManipulator\Application;

use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Domain\Shared\Bus\Query\QueryHandlerInterface;
use App\ImageManipulator\Domain\Shared\Bus\Query\ResponseInterface;
use App\ImageManipulator\Domain\ValueObject\ImageName;

class ImageDownloadQueryHandler implements QueryHandlerInterface, ResponseInterface
{
    public function __construct(
        private readonly FileRepositoryInterface $repository
    ) {
    }

    public function __invoke(ImageDownloadQuery $query): ResponseInterface
    {
        $imageName = ImageName::stringify($query->getImageName());

        $this->repository->locateModifiedImage($imageName->value());

        $imageContent = $this->repository->getModifiedImage($imageName->value());
        $imageData = $this->repository->getModifiedImageData($imageName->value());

        return new ImageResponse($imageName->value(), $imageContent, $imageData['mime']);
    }
}
