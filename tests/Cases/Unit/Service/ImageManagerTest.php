<?php

namespace App\Tests\Cases\Unit\Service;

use App\Service\ImageManager;
use Tests\UnitTestCase;

final class ImageManagerTest extends UnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new ImageManager();
    }

    public function testShouldReturnsPath(): void
    {
        $imageManager = new ImageManager();
        $path = '/images/test.png';

        $start = time();
        $result = $imageManager->manage($path);
        $elapsed = time() - $start;

        self::assertSame($path, $result);
        self::assertGreaterThanOrEqual(35, $elapsed, 'La méthode manage() doit prendre au moins 35 secondes.');
    }
}
