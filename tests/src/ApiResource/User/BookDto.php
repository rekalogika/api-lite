<?php

declare(strict_types=1);

namespace App\ApiResource\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\ApiState\User\Book\BookCollectionProvider;
use App\ApiState\User\Book\BookProvider;
use Rekalogika\Mapper\CollectionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'User/Book',
    routePrefix: '/user',
    operations: [
        new Get(
            uriTemplate: '/books/{id}',
            provider: BookProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/books',
            provider: BookCollectionProvider::class,
        ),
    ]
)]
class BookDto
{
    public ?Uuid $id = null;
    public ?string $title = null;
    public ?string $description = null;

    /**
     * @var ?CollectionInterface<int,ReviewDto>
     */
    #[ApiProperty(uriTemplate: '/books/{bookId}/reviews')]
    public ?CollectionInterface $reviews = null;
}
