<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\Admin\BookInputDto;
use App\ApiResource\Admin\BookDto;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<BookInputDto,BookDto>
 */
class BookUpdateProcessor extends AbstractProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private BookRepository $bookRepository,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        // Get the book from the database
        $book = $this->bookRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Check for authorization
        $this->denyAccessUnlessGranted('update', $book);

        // Update the book by mapping the input DTO to the entity. In a more
        // complex scenario you might need to do this differently, possibly
        // without the mapper
        $this->map($data, $book);

        // Flush the changes to the database
        $this->entityManager->flush();

        // Map the resulting Book to the output DTO, and return it.
        return $this->map($book, BookDto::class);
    }
}
