<?php

declare(strict_types=1);

namespace App\ApiState\User\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\BookDto;
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
        $this->denyAccessUnlessGranted('view', $this->bookRepository);

        return $this->mapCollection(
            collection: $this->bookRepository,
            target: BookDto::class,
            operation: $operation,
            context: $context
        );
    }
}
