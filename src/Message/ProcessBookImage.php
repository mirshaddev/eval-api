<?php

namespace App\Message;

final readonly class ProcessBookImage
{
    public function __construct(
        public string $path,
    ) {
    }
}