<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\Controller\BookImageController;
use App\Repository\BookRepository;
use ArrayObject;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        new Get(),
        new GetCollection(),
        new Post(),
        new Patch(),
        new Post(
            uriTemplate: '/books/{id}/image',
            controller: BookImageController::class,
            deserialize: false,
            write: false,
            status: 202,
            inputFormats: ['multipart' => ['multipart/form-data']],
            openapi: new Model\Operation(
                summary: 'Ajouter une image de couverture à un livre',
                description: 'Formats autorisés : JPEG et PNG. Taille maximale : 5 Mo.',
                requestBody: new Model\RequestBody(
                    required: true,
                    content: new ArrayObject([
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'required' => ['image'],
                                'properties' => [
                                    'image' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ),
            ),
        ),
    ]
)]
#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: 'app_book')]
#[ORM\HasLifecycleCallbacks]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\SequenceGenerator(sequenceName: 'app_book_id_seq', allocationSize: 1)]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 150)]
    private string $title;

    #[ORM\Column(type: Types::FLOAT)]
    private float $price;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $resume = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ApiProperty(writable: false)]
    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    #[ApiProperty(writable: false)]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageUrl = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getResume(): ?string
    {
        return $this->resume;
    }

    public function setResume(?string $resume): static
    {
        $this->resume = $resume;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function initializeCreatedAt(): void
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }
}
