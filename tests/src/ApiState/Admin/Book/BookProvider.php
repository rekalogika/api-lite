<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\Admin\BookDto;
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
        // Get the book from the database
        $book = $this->bookRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Check for authorization
        $this->denyAccessUnlessGranted('view', $book);

        // Map the Book to the output DTO, and return it.
        return $this->map($book, BookDto::class);
    }
}
