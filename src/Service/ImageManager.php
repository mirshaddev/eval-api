<?php

namespace App\Service;

final class ImageManager implements ImageManagerInterface
{
    public function manage(string $path): string
    {
        // Simulate image processing
        sleep(35);

        return $path;
    }
}
