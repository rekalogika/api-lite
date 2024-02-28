<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\Admin\BookDto;
use App\Repository\BookRepository;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<BookDto>
 */
class BookCollectionProvider extends AbstractProvider
{
    public function __construct(
        private BookRepository $bookRepository
    ) {
    }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object|array|null {
        // Check for authorization
        $this->denyAccessUnlessGranted('view', $this->bookRepository);

        // A Doctrine repository implements Selectable, and our PaginatorApplier
        // supports Selectable, so we can convieniently use it as a collection
        // of entities to map. Here we map the Books to BookDtos, and return
        // them.
        return $this->mapCollection(
            collection: $this->bookRepository,
            target: BookDto::class,
            operation: $operation,
            context: $context
        );
    }
}
