<?php

namespace App\MessageHandler;

use App\Message\ProcessBookImage;
use App\Service\ImageManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final readonly class ProcessBookImageHandler
{
    public function __construct(
        private ImageManagerInterface $imageManager,
        private string $projectDir,
    ) {
    }

    public function __invoke(ProcessBookImage $message): void
    {
        $absolutePath = $this->projectDir.'/'.ltrim($message->path, '/');

        $this->imageManager->manage($absolutePath);
    }
}