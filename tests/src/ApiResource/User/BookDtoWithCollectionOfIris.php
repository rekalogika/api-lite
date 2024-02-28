<?php

declare(strict_types=1);

namespace App\ApiResource\User;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use App\ApiState\User\Book\BookProviderWithCollectionOfIris;
use Rekalogika\Mapper\CollectionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'User/Book',
    routePrefix: '/user',
    operations: [
        new Get(
            uriTemplate: '/books/withCollectionOfIris/{id}',
            provider: BookProviderWithCollectionOfIris::class,
        ),
    ]
)]
class BookDtoWithCollectionOfIris
{
    public ?Uuid $id = null;
    public ?string $title = null;
    public ?string $description = null;

    /**
     * @var ?CollectionInterface<int,ReviewDto>
     */
    #[ApiProperty(readableLink: false)]
    public ?CollectionInterface $reviews = null;
}
