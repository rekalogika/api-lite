<?php

declare(strict_types=1);

/*
 * This file is part of rekalogika/api-lite package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\ApiLite\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Symfony\Bundle\Test\Client;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class ApiLiteTestCase extends ApiTestCase
{
    protected function createAdminClient(): Client
    {
        return static::createClient([], [
            'headers' => [
                'authorization' => 'Bearer admin',
                'content-type' => 'application/ld+json',
            ],
        ]);
    }

    protected function createUserClient(): Client
    {
        return static::createClient([], [
            'headers' => [
                'authorization' => 'Bearer user',
                'content-type' => 'application/ld+json',
            ],
        ]);
    }

    protected function getBookRepository(): BookRepository
    {
        $managerRegistry = static::getContainer()->get('doctrine');
        $this->assertInstanceOf(ManagerRegistry::class, $managerRegistry);

        $repository = $managerRegistry->getRepository(Book::class);
        $this->assertInstanceOf(BookRepository::class, $repository);

        return $repository;
    }

    protected function getEntityManager(): EntityManagerInterface
    {
        $managerRegistry = static::getContainer()->get('doctrine');
        $this->assertInstanceOf(ManagerRegistry::class, $managerRegistry);

        $entityManager = $managerRegistry->getManager();
        $this->assertInstanceOf(EntityManagerInterface::class, $entityManager);

        return $entityManager;
    }

    protected function getABook(): Book
    {
        return $this->getBookRepository()->findOneBy([]) ?? throw new \RuntimeException('No book found');
    }


}
