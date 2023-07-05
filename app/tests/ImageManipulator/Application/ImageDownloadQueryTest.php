<?php

namespace Test\ImageManipulator\Application;

use App\ImageManipulator\Application\ImageDownloadQuery;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ImageDownloadQuery
 */
class ImageDownloadQueryTest extends TestCase
{
    private const IMAGE_NAME = 'Foo';

    public function testQueryReturnsInjectedImageName(): void
    {
        $query = new ImageDownloadQuery(self::IMAGE_NAME);

        $this->assertEquals(self::IMAGE_NAME, $query->getImageName());
    }
}
