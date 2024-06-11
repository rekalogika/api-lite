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
use ApiPlatform\OpenApi\Model\Parameter;
use App\ApiInput\Admin\BookInputDto;
use App\ApiState\Admin\Book\BookCheckProcessor;
use App\ApiState\Admin\Book\BookCollectionProvider;
use App\ApiState\Admin\Book\BookCollectionWithSearchProvider;
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
        new GetCollection(
            uriTemplate: '/books-with-search',
            provider: BookCollectionWithSearchProvider::class,
            openapi: new Operation(
                parameters: [
                    new Parameter(
                        name: 'search',
                        in: 'query',
                        description: 'Search for books',
                        required: false,
                        schema: ['type' => 'string']
                    )
                ]
            )
        ),
        new GetCollection(
            uriTemplate: '/books-with-keyset-pagination',
            provider: BookCollectionProvider::class,
            extraProperties: [
                'api_lite_rekapager' => true
            ]
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
            provider: BookProvider::class,
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
