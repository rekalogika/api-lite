<?php

declare(strict_types=1);

namespace App\ApiResource\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Post;
use App\ApiInput\User\ReviewInputDto;
use App\ApiInput\User\ReviewInputDtoWithBook;
use App\ApiInput\User\ReviewInputDtoWithBookDtoPropertyWritableLinkFalse;
use App\ApiInput\User\ReviewInputDtoWithBookDtoPropertyWritableLinkTrue;
use App\ApiInput\User\ReviewInputDtoWithBookInputDtoProperty;
use App\ApiState\User\Review\BookReviewCollectionProvider;
use App\ApiState\User\Review\BookReviewCreateUnderBookIriProcessor;
use App\ApiState\User\Review\BookReviewCreateWithBookDtoProperty;
use App\ApiState\User\Review\BookReviewCreateWithBookDtoPropertyAndBookPersistedManually;
use App\ApiState\User\Review\BookReviewCreateWithBookInputDtoProperty;
use App\ApiState\User\Review\BookReviewProvider;
use App\ApiState\User\Review\ReviewCollectionProvider;
use App\ApiState\User\Review\ReviewCreateAndAddReviewToBookProcessor;
use App\ApiState\User\Review\ReviewProvider;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'User/Review',
    routePrefix: '/user',
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
        new Post(
            uriTemplate: '/reviews',
            input: ReviewInputDtoWithBook::class,
            processor: ReviewCreateAndAddReviewToBookProcessor::class,
        ),
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
        new Post(
            uriTemplate: '/books/{bookId}/reviews',
            uriVariables: [
                'bookId' => new Link(
                    fromClass: BookDto::class,
                )
            ],
            input: ReviewInputDto::class,
            processor: BookReviewCreateUnderBookIriProcessor::class
        ),
        new Post(
            uriTemplate: '/reviews/withBookDtoWritableLinkTrue',
            input: ReviewInputDtoWithBookDtoPropertyWritableLinkTrue::class,
            processor: BookReviewCreateWithBookDtoProperty::class,
            deprecationReason: 'We do not consider this a good practice.',
        ),
        new Post(
            uriTemplate: '/reviews/withBookDtoWritableLinkTruePersistedManually',
            input: ReviewInputDtoWithBookDtoPropertyWritableLinkTrue::class,
            processor: BookReviewCreateWithBookDtoPropertyAndBookPersistedManually::class,
            deprecationReason: 'We do not consider this a good practice.',
        ),
        new Post(
            uriTemplate: '/reviews/withBookDtoWritableLinkFalse',
            input: ReviewInputDtoWithBookDtoPropertyWritableLinkFalse::class,
            processor: BookReviewCreateWithBookDtoProperty::class,
            deprecationReason: 'We do not consider this a good practice.',
        ),
        new Post(
            uriTemplate: '/reviews/withBookInputDto',
            input: ReviewInputDtoWithBookInputDtoProperty::class,
            processor: BookReviewCreateWithBookInputDtoProperty::class,
            deprecationReason: 'We do not consider this a good practice.',
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
