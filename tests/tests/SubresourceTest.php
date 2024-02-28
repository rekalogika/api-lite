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

class SubresourceTest extends ApiLiteTestCase
{
    public function testGetCollectionSubresource(): void
    {
        $book = $this->getABook();

        $response = static::createUserClient()->request(
            'GET',
            sprintf('/user/books/%s/reviews', $book->getId())
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/User/Review',
            '@id' => sprintf('/user/books/%s/reviews', $book->getId()),
            '@type' => 'hydra:Collection',
        ]);
    }

    public function testGetSubresource(): void
    {
        $book = $this->getABook();
        $review = $book->getReviews()->first();

        $this->assertNotFalse($review);

        $response = static::createUserClient()->request(
            'GET',
            sprintf('/user/books/%s/reviews/%s', $book->getId(), $review->getId())
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@id' => sprintf('/user/books/%s/reviews/%s', $book->getId(), $review->getId()),
            '@type' => 'User/Review',
            'id' => (string) $review->getId(),
            'body' => $review->getBody(),
        ]);
    }

    public function testGetWithSubresourceRepresentedByIriOfCollection(): void
    {
        $book = $this->getABook();

        $response = static::createUserClient()->request(
            'GET',
            sprintf('/user/books/%s', $book->getId())
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/User/Book',
            '@id' => sprintf('/user/books/%s', $book->getId()),
            '@type' => 'User/Book',
        ]);

        $reviewIri = $response->toArray()['reviews'];
        $this->assertIsString($reviewIri);
        $this->assertMatchesRegularExpression(
            '|^/user/books/[a-z0-9-]+/reviews$|',
            $reviewIri
        );
    }

    public function testGetWithSubresourceRepresentedByCollectionOfIris(): void
    {
        $book = $this->getABook();

        $response = static::createUserClient()->request(
            'GET',
            sprintf('/user/books/withCollectionOfIris/%s', $book->getId())
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/User/Book',
            '@id' => sprintf('/user/books/withCollectionOfIris/%s', $book->getId()),
            '@type' => 'User/Book',
        ]);

        $reviewIris = $response->toArray()['reviews'];
        $this->assertIsArray($reviewIris);

        foreach ($reviewIris as $reviewIri) {
            $this->assertIsString($reviewIri);
            $this->assertMatchesRegularExpression(
                '|^/user/reviews/[a-z0-9-]+$|',
                $reviewIri
            );
        }
    }

    public function testGetWithSubresourceRepresentedByCollectionOfEmbeddedObjects(): void
    {
        $book = $this->getABook();

        $response = static::createUserClient()->request(
            'GET',
            sprintf('/user/books/withEmbeddedObjects/%s', $book->getId())
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            '@context' => '/contexts/User/Book',
            '@id' => sprintf('/user/books/withEmbeddedObjects/%s', $book->getId()),
            '@type' => 'User/Book',
        ]);

        $reviews = $response->toArray()['reviews'];
        $this->assertIsArray($reviews);

        foreach ($reviews as $review) {
            $this->assertIsArray($review);
            $this->assertArrayHasKey('id', $review);
            $this->assertArrayHasKey('body', $review);
        }
    }
}
