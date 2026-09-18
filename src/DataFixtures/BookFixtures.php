<?php

namespace App\DataFixtures;

use App\Entity\Book;
use App\Service\Fixtures\JsonFixturesReaderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

final class BookFixtures extends Fixture
{
    public function __construct(
        private readonly JsonFixturesReaderInterface $reader,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $data = $this->reader->read('books');

        foreach ($data as $datum) {
            $user = new Book()
                ->setTitle($datum['title'])
                ->setPrice($datum['price'])
                ->setResume($datum['resume']);

            $manager->persist($user);
        }

        $manager->flush();
    }
}
