<?php

declare(strict_types=1);

namespace App\ApiResource\Admin;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation;
use App\ApiInput\Admin\BookInputDto;
use App\ApiState\Admin\Book\BookCheckProcessor;
use App\ApiState\Admin\Book\BookCollectionProvider;
use App\ApiState\Admin\Book\BookCreateProcessor;
use App\ApiState\Admin\Book\BookProvider;
use App\ApiState\Admin\Book\BookRemoveProcessor;
use App\ApiState\Admin\Book\BookUpdateProcessor;
use Rekalogika\Mapper\CollectionInterface;
use Symfony\Component\Uid\Uuid;

#[ApiResource(
    shortName: 'Admin/Book',
    routePrefix: '/admin',
    operations: [
        new Get(
            uriTemplate: '/books/{id}',
            provider: BookProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/books',
            provider: BookCollectionProvider::class,
        ),
        new Post(
            uriTemplate: '/books',
            input: BookInputDto::class,
            processor: BookCreateProcessor::class
        ),
        new Put(
            uriTemplate: '/books/{id}',
            input: BookInputDto::class,
            processor: BookUpdateProcessor::class,
            read: false,
        ),
        new Patch(
            uriTemplate: '/books/{id}',
            input: BookInputDto::class,
            processor: BookUpdateProcessor::class,
            read: false,
        ),
        new Delete(
            uriTemplate: '/books/{id}',
            processor: BookRemoveProcessor::class,
            read: false
        ),
        new Post(
            uriTemplate: '/books/{id}/check',
            processor: BookCheckProcessor::class,
            input: false,
            openapi: new Operation(
                summary: 'Check the book\'s condition',
                description: 'Tells us that the book condition has been checked.',
            )
        ),
    ]
)]
class BookDto
{
    public ?Uuid $id = null;
    public ?string $title = null;
    public ?string $description = null;
    public ?\DateTimeInterface $lastChecked = null;

    /**
     * @var ?CollectionInterface<int,ReviewDto>
     */
    #[ApiProperty(uriTemplate: '/books/{bookId}/reviews')]
    public ?CollectionInterface $reviews = null;
}
