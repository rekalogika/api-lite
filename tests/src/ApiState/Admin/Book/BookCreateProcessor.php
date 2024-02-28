<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\Admin\BookInputDto;
use App\ApiResource\Admin\BookDto;
use App\Entity\Book;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<BookInputDto,BookDto>
 */
class BookCreateProcessor extends AbstractProcessor
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
        // Check for authorization
        $this->denyAccessUnlessGranted('add', $this->bookRepository);

        // Map the input DTO to the Book entity. In more complex scenarios,
        // using the mapper can be impractical, and you need to do it according
        // to the requirement of your domain logic.
        $book = $this->map($data, Book::class);

        // Persist the entity and flush the changes
        $this->entityManager->persist($book);
        $this->entityManager->flush();

        // Map the resulting Book to the output DTO, and return it.
        return $this->map($book, BookDto::class);
    }
}
