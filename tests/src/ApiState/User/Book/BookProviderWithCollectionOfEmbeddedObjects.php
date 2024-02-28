<?php

declare(strict_types=1);

namespace App\ApiState\User\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\BookDtoWithEmbeddedObjects;
use App\Repository\BookRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<BookDtoWithEmbeddedObjects>
 */
class BookProviderWithCollectionOfEmbeddedObjects extends AbstractProvider
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
        $book = $this->bookRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        $this->denyAccessUnlessGranted('view', $book);

        return $this->map($book, BookDtoWithEmbeddedObjects::class);
    }
}
