<?php

namespace Tests\Cases\Unit\Service\Fixtures;

use App\Service\Fixtures\JsonFixturesReader;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Tests\UnitTestCase;

final class JsonFixturesReaderTest extends UnitTestCase
{
    private Filesystem $filesystem;
    private JsonFixturesReader $subject;

    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem = self::createMock(Filesystem::class);

        $this->subject = new JsonFixturesReader(self::$fake->slug(), $this->filesystem);
    }

    public function testThrowsWhenFileNotFound(): void
    {
        $this->filesystem->expects($this->once())->method('exists')->willReturn(false);

        self::expectException(RuntimeException::class);
        self::expectExceptionMessageMatches('/not found/');

        $this->subject->read('test');
    }

    public function testThrowsOnInvalidJson(): void
    {
        $this->filesystem->expects($this->once())->method('exists')->willReturn(true);
        $this->filesystem->expects($this->once())->method('readFile')->willReturn('invalid json');

        self::expectException(RuntimeException::class);
        self::expectExceptionMessageMatches('/invalid JSON/');

        $this->subject->read('test');
    }

    public function testLoadsWhenValidJson(): void
    {
        $this->filesystem->expects($this->once())->method('exists')->willReturn(true);
        $this->filesystem->expects($this->once())->method('readFile')->willReturn('[{"test": "value"}]');

        $result = $this->subject->read('test');

        self::assertEquals([['test' => 'value']], $result);
    }
}
