<?php

namespace App\Service;

interface ImageManagerInterface
{
    public function manage(string $path): string;
}
