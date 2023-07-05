<?php

namespace Test\ImageManipulator\Application;

use App\ImageManipulator\Application\ImageDownloadQuery;
use App\ImageManipulator\Application\ImageDownloadQueryHandler;
use App\ImageManipulator\Application\ImageResponse;
use App\ImageManipulator\Domain\FileRepositoryInterface;
use App\ImageManipulator\Infrastructure\Http\Exception\ImageNotFoundException;
use Mockery as m;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ImageDownloadQueryHandler
 */
class ImageDownloadQueryHandlerTest extends TestCase
{
    private const IMAGE_NAME = 'Foo';
    private const IMAGE_CONTENT = 'Bar';
    private const IMAGE_MIME = 'image/gif';

    public function tearDown(): void
    {
        m::close();

        parent::tearDown();
    }

    public function testImageIsCorrectlyReturned(): void
    {
        $expectedImageData = [
            100, 100, 1, 'width="100" height="100', 'bits' => 8, 'channels' => 3, 'mime' => self::IMAGE_MIME,
        ];

        $expectedResponse = new ImageResponse(self::IMAGE_NAME, self::IMAGE_CONTENT, self::IMAGE_MIME);

        $query = new ImageDownloadQuery(self::IMAGE_NAME);

        $repository = m::mock(FileRepositoryInterface::class);
        $repository->shouldReceive('locateModifiedImage')
            ->with(self::IMAGE_NAME);

        $repository->shouldReceive('getModifiedImage')
            ->with(self::IMAGE_NAME)
            ->andReturns(self::IMAGE_CONTENT);

        $repository->shouldReceive('getModifiedImageData')
            ->with(self::IMAGE_NAME)
            ->andReturns($expectedImageData);

        $handler = new ImageDownloadQueryHandler($repository);
        $response = $handler->__invoke($query);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testImageIsNotFound(): void
    {
        $this->expectException(ImageNotFoundException::class);

        $query = new ImageDownloadQuery(self::IMAGE_NAME);

        $repository = m::mock(FileRepositoryInterface::class);
        $repository->shouldReceive('locateModifiedImage')
            ->with(self::IMAGE_NAME)
            ->andThrows(ImageNotFoundException::class);

        $handler = new ImageDownloadQueryHandler($repository);
        $handler->__invoke($query);
    }
}
