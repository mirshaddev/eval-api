<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Book;
use App\Message\ProcessBookImage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[AsController]
final class BookImageController extends AbstractController
{
    public function __construct(
        private readonly string $bookImagesDirectory,
    ) {
    }

    public function __invoke(
        Book $data,
        Request $request,
        ValidatorInterface $validator,
        SluggerInterface $slugger,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus,
    ): JsonResponse {
        $image = $request->files->get('image');

        if (!$image instanceof UploadedFile) {
            throw new BadRequestHttpException('Le champ image est obligatoire.');
        }

        $violations = $validator->validate($image, new File(
            maxSize: '5M',
            mimeTypes: ['image/jpeg', 'image/png'],
            mimeTypesMessage: 'Seules les images JPEG et PNG sont autorisées.',
        ));

        if (count($violations) > 0) {
            throw new UnprocessableEntityHttpException((string) $violations);
        }

        $oldImageUrl = $data->getImageUrl();

        $baseName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $safeName = $slugger->slug($baseName);
        $fileName = $safeName.'-'.uniqid().'.'.$image->guessExtension();

        $image->move($this->bookImagesDirectory, $fileName);

        $imageUrl = '/uploads/books/'.$fileName;
        $data->setImageUrl($imageUrl);

        $entityManager->flush();

        if ($oldImageUrl !== null) {
            $oldImagePath = $this->bookImagesDirectory.'/'.basename($oldImageUrl);

            if (is_file($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        $messageBus->dispatch(new ProcessBookImage('public'.$imageUrl));

        return $this->json($data, Response::HTTP_ACCEPTED);
    }
}
