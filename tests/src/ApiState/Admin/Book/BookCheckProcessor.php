<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\Admin\BookDto;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<void,BookDto>
 */
class BookCheckProcessor extends AbstractProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        // Gets the book from the database
        $book = $this->bookRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Check for authorization
        $this->denyAccessUnlessGranted('check', $book);

        // Execute the action
        $book->check();

        // Flush any changes to the database
        $this->entityManager->flush();

        // Map the Book to the output DTO, and return it.
        return $this->map($book, BookDto::class);
    }
}
