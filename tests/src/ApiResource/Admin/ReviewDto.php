<?php

declare(strict_types=1);

namespace App\ApiResource\Admin;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use App\ApiState\Admin\Review\BookReviewCollectionProvider;
use App\ApiState\Admin\Review\BookReviewProvider;
use App\ApiState\Admin\Review\ReviewCollectionProvider;
use App\ApiState\Admin\Review\ReviewProvider;
use App\ApiState\Admin\Review\ReviewRemoveProcessor;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Admin/Review',
    routePrefix: '/admin',
    operations: [
        // top level

        new Get(
            uriTemplate: '/reviews/{id}',
            provider: ReviewProvider::class
        ),
        new GetCollection(
            uriTemplate: '/reviews',
            provider: ReviewCollectionProvider::class,
            paginationItemsPerPage: 10
        ),
        new Delete(
            uriTemplate: '/reviews/{id}',
            processor: ReviewRemoveProcessor::class,
            // for some reason `read: false` here is no longer working, in the
            // meantime, we can add `provider` to make it work. and it only
            // applies to `Delete` operation.

            // Error message when `read: false` is used: Uncaught PHP Exception
            // RuntimeException: "Controller
            // "ApiPlatform\Action\PlaceholderAction::__invoke" requires the
            // "$data" argument that could not be resolved. Either the argument
            // is nullable and no null value has been provided, no default value
            // has been provided or there is a non-optional argument after this
            // one.
            provider: ReviewProvider::class
        ),

        // under book

        new Get(
            uriTemplate: '/books/{bookId}/reviews/{id}',
            provider: BookReviewProvider::class,
            uriVariables: [
                'bookId' => new Link(
                    fromClass: BookDto::class,
                ),
                'id' => new Link(
                    fromClass: self::class,
                ),
            ],
        ),
        new GetCollection(
            uriTemplate: '/books/{bookId}/reviews',
            uriVariables: [
                'bookId' => new Link(
                    fromClass: BookDto::class,
                ),
            ],
            provider: BookReviewCollectionProvider::class,
            paginationItemsPerPage: 10
        ),
    ]
)]
class ReviewDto
{
    public ?Uuid $id = null;
    public ?string $body = null;

    /**
     * @var int<1,5>|null
     */
    public ?int $rating = null;

    #[ApiProperty(readableLink: false)]
    public ?BookDto $book = null;
}
