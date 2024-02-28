<?php

declare(strict_types=1);

namespace App\ApiState\User\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\BookDto;
use App\Repository\BookRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<BookDto>
 */
class BookProvider extends AbstractProvider
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
        // Get the book from the repository
        $book = $this->bookRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Check if the user has access to the book
        $this->denyAccessUnlessGranted('view', $book);

        // Map the book to the DTO, and return it
        return $this->map($book, BookDto::class);
    }
}
