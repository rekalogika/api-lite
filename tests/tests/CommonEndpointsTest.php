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

use App\Entity\Book;

class CommonEndpointsTest extends ApiLiteTestCase
{
    public function testGetCollection(): void
    {
        $response = static::createAdminClient()->request('GET', '/admin/books');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/Admin/Book',
            '@id' => '/admin/books',
            '@type' => 'hydra:Collection',
        ]);
    }

    public function testGetCollectionKeysetPagination(): void
    {
        $response = static::createAdminClient()->request('GET', '/admin/books-with-keyset-pagination');
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/Admin/Book',
            '@id' => '/admin/books-with-keyset-pagination',
            '@type' => 'hydra:Collection',
        ]);

        $nextUrl = $response->toArray()['hydra:view']['hydra:next'] ?? null;
        $lastUrl = $response->toArray()['hydra:view']['hydra:last'] ?? null;
        static::assertIsString($nextUrl);
        static::assertIsString($lastUrl);

        $nextResponse = static::createAdminClient()->request('GET', $nextUrl);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/Admin/Book',
            // '@id' => $nextUrl,
            '@type' => 'hydra:Collection',
        ]);

        $lastResponse = static::createAdminClient()->request('GET', $lastUrl);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/Admin/Book',
            // '@id' => $lastUrl,
            '@type' => 'hydra:Collection',
        ]);

    }

    public function testGet(): void
    {
        $book = $this->getABook();

        $response = static::createAdminClient()->request(
            'GET',
            \sprintf('/admin/books/%s', $book->getId()),
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => \sprintf('/admin/books/%s', $book->getId()),
            '@type' => 'Admin/Book',
            'id' => (string) $book->getId(),
            'title' => $book->getTitle(),
            'description' => $book->getDescription(),
        ]);
    }

    public function testPost(): void
    {
        $response = static::createAdminClient()->request(
            'POST',
            '/admin/books',
            [
                'json' => [
                    'title' => 'New Book',
                    'description' => 'New Description',
                ],
            ],
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'title' => 'New Book',
            'description' => 'New Description',
        ]);
    }

    public function testPut(): void
    {
        $book = $this->getABook();

        $response = static::createAdminClient()->request(
            'PUT',
            \sprintf('/admin/books/%s', $book->getId()),
            [
                'json' => [
                    'title' => 'Updated Book',
                    'description' => 'Updated Description',
                ],
            ],
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'title' => 'Updated Book',
            'description' => 'Updated Description',
        ]);

        $book = $this->getBookRepository()->find($book->getId());
        $this->assertInstanceOf(Book::class, $book);
        $this->assertSame('Updated Book', $book->getTitle());
        $this->assertSame('Updated Description', $book->getDescription());
    }

    public function testPatch(): void
    {
        $book = $this->getABook();
        $description = $book->getDescription();

        $response = static::createAdminClient()->request(
            'PATCH',
            \sprintf('/admin/books/%s', $book->getId()),
            [
                'json' => [
                    'title' => 'Patched Book',
                ],
                'headers' => [
                    'Content-Type' => 'application/merge-patch+json',
                ],
            ],
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'title' => 'Patched Book',
        ]);

        $book = $this->getBookRepository()->find($book->getId());
        $this->assertInstanceOf(Book::class, $book);
        $this->assertSame('Patched Book', $book->getTitle());
        $this->assertSame($description, $book->getDescription());
    }

    public function testDelete(): void
    {
        $book = $this->getABook();

        $response = static::createAdminClient()->request(
            'DELETE',
            \sprintf('/admin/books/%s', $book->getId()),
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(204);

        $book = $this->getBookRepository()->find($book->getId());
        $this->assertNull($book);
    }

    public function testAction(): void
    {
        $book = $this->getABook();
        $this->assertNull($book->getLastChecked());

        $response = static::createAdminClient()->request(
            'POST',
            \sprintf('/admin/books/%s/check', $book->getId()),
            [
                'headers' => [
                    'Content-Type' => false,
                ],
            ],
        );

        $book = $this->getBookRepository()->find($book->getId());
        $this->assertInstanceOf(Book::class, $book);
        $this->assertNotNull($book->getLastChecked());
    }
}
