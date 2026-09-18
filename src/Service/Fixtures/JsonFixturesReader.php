<?php

namespace App\Service\Fixtures;

use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

final readonly class JsonFixturesReader implements JsonFixturesReaderInterface
{
    private const string PATTERN = '/config/fixtures/%s.json';

    public function __construct(
        private string $rootDir,
        private Filesystem $filesystem = new Filesystem(),
    ) {
    }

    public function read(string $basename): array
    {
        $filepath = $this->rootDir.sprintf(self::PATTERN, $basename);

        if (!$this->filesystem->exists($filepath)) {
            throw new RuntimeException(sprintf('Fixture file "%s" not found.', $filepath));
        }

        $content = $this->filesystem->readFile($filepath);
        $data = json_decode($content, true);

        if (!is_array($data)) {
            throw new RuntimeException(sprintf('Fixture file "%s" contains invalid JSON.', $filepath));
        }

        return $data;
    }
}
