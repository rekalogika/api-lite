<?php

declare(strict_types=1);

namespace App\ApiResource\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\ApiState\User\Review\ReviewProviderWithEmbeddedResource;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'User/Review',
    routePrefix: '/user',
    operations: [
        new Get(
            uriTemplate: '/reviewsWithEmbeddedResource/{id}',
            provider: ReviewProviderWithEmbeddedResource::class
        ),
    ]
)]
class ReviewDtoWithEmbeddedResource
{
    public ?Uuid $id = null;
    public ?string $body = null;

    /**
     * @var int<1,5>|null
     */
    public ?int $rating = null;

    #[ApiProperty(readableLink: true)]
    public ?BookDto $book = null;
}
