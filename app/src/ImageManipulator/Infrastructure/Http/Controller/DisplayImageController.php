<?php

namespace App\ImageManipulator\Infrastructure\Http\Controller;

use App\ImageManipulator\Application\ImageDownloadQuery;
use App\ImageManipulator\Application\ImageResponse;
use App\ImageManipulator\Domain\Shared\Bus\Query\QueryBusInterface;

class DisplayImageController
{
    public function __construct(private readonly QueryBusInterface $queryBus)
    {
    }

    public function __invoke(array $requestParameters): array
    {
        $command = new ImageDownloadQuery(
            array_shift($requestParameters)
        );

        /** @var ImageResponse $result */
        $result = $this->queryBus->ask($command);

        $response['content_type'] = 'Content-Type: '.$result->getImageMime();
        $response['status_code'] = 'HTTP/1.1 200 OK';
        $response['body'] = $result->getImageContent();

        return $response;
    }
}
