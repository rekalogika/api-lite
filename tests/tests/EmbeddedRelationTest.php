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
use App\Entity\Review;

class EmbeddedRelationTest extends ApiLiteTestCase
{
    public function testPostWithRelatedIdInUrl(): void
    {
        $book = $this->getABook();

        $response = static::createUserClient()->request(
            'POST',
            sprintf('/user/books/%s/reviews', $book->getId()),
            [
                'json' => [
                    'body' => 'New Review',
                    'rating' => 5,
                ]
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'body' => 'New Review',
        ]);

        $book = $this->getBookRepository()->find($book->getId());
        $this->assertInstanceOf(Book::class, $book);
        $review = $book->getReviews()->last();
        $this->assertInstanceOf(Review::class, $review);
        $this->assertSame('New Review', $review->getBody());
    }

    public function testPostWithNewRelatedObjectInBodyUsingBookInputDto(): void
    {
        $reviewBody = 'New Review ' . uniqid();
        $bookTitle = 'New Book ' . uniqid();
        $bookDescription = 'New Description ' . uniqid();

        $response = static::createUserClient()->request(
            'POST',
            '/user/reviews/withBookInputDto',
            [
                'json' => [
                    'body' => $reviewBody,
                    'rating' => 5,
                    'book' => [
                        'title' => $bookTitle,
                        'description' => $bookDescription,
                    ]
                ],
                'headers' => [
                    'Content-Type' => 'application/ld+json'
                ]
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'body' => $reviewBody,
        ]);

        $bookIri = $response->toArray()['book'];
        $this->assertIsString($bookIri);
        $this->assertMatchesRegularExpression(
            '|^/user/books/[a-z0-9-]+$|',
            $bookIri
        );

        $response = static::createUserClient()->request('GET', $bookIri);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'title' => $bookTitle,
            'description' => $bookDescription,
        ]);
    }

    public function testPostWithExistingRelatedObjectIriInBody(): void
    {
        $book = $this->getABook();

        $reviewBody = 'New Review ' . uniqid();

        $response = static::createUserClient()->request(
            'POST',
            '/user/reviews/withBookDtoWritableLinkTrue',
            [
                'json' => [
                    'body' => $reviewBody,
                    'rating' => 5,
                    'book' => sprintf('/user/books/%s', $book->getId()),
                ],
                'headers' => [
                    'Content-Type' => 'application/ld+json'
                ]
            ]
        );

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'body' => $reviewBody,
        ]);

        $this->getEntityManager()->clear();

        $book = $this->getBookRepository()->find($book->getId());
        $this->assertInstanceOf(Book::class, $book);
        $review = $book->getReviews()->last();
        $this->assertInstanceOf(Review::class, $review);
        $this->assertSame($reviewBody, $review->getBody());
    }

    // public function testPostWithExistingRelatedObjectIdInBody(): void
    // {
    //     $this->getEntityManager()->clear();

    //     $book = $this->getABook();

    //     $reviewBody = 'New Review ' . uniqid();

    //     $response = static::createUserClient()->request(
    //         'POST',
    //         '/user/reviews/withBookDtoWritableLinkTrue',
    //         [
    //             'json' => [
    //                 'book' => [
    //                     '@id' => sprintf('/user/books/%s', $book->getId()),
    //                 ],
    //                 'body' => $reviewBody,
    //                 'rating' => 5,
    //             ],
    //             'headers' => [
    //                 'Content-Type' => 'application/ld+json'
    //             ]
    //         ]
    //     );

    //     $this->getEntityManager()->clear();

    //     $this->assertResponseIsSuccessful();
    //     $this->assertJsonContains([
    //         'body' => $reviewBody,
    //         'book' => sprintf('/user/books/%s', $book->getId()),
    //     ]);

    //     $this->getEntityManager()->clear();

    //     $book = $this->getBookRepository()->find($book->getId());
    //     $this->assertInstanceOf(Book::class, $book);
    //     $review = $book->getReviews()->last();
    //     $this->assertInstanceOf(Review::class, $review);
    //     $this->assertSame($reviewBody, $review->getBody());
    // }

    public function testPostWithNewRelatedObject(): void
    {
        $this->getEntityManager()->clear();

        $reviewBody = 'New Review ' . uniqid();
        $bookTitle = 'New Book ' . uniqid();
        $bookDescription = 'New Description ' . uniqid();

        $response = static::createUserClient()->request(
            'POST',
            '/user/reviews/withBookDtoWritableLinkTruePersistedManually',
            [
                'json' => [
                    'book' => [
                        'title' => $bookTitle,
                        'description' => $bookDescription,
                    ],
                    'body' => $reviewBody,
                    'rating' => 5,
                ],
                'headers' => [
                    'Content-Type' => 'application/ld+json'
                ]
            ]
        );

        $this->getEntityManager()->clear();

        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'body' => $reviewBody,
        ]);

        $bookIri = $response->toArray()['book'];
        $this->assertIsString($bookIri);
        $this->assertMatchesRegularExpression(
            '|^/user/books/[a-z0-9-]+$|',
            $bookIri
        );

        $response = static::createUserClient()->request('GET', $bookIri);
        $this->assertResponseIsSuccessful();
        $this->assertJsonContains([
            'title' => $bookTitle,
            'description' => $bookDescription,
        ]);
    }
}
