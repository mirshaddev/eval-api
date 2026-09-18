<?php

namespace App\Service\Fixtures;

interface JsonFixturesReaderInterface
{
    public function read(string $basename): array;
}
