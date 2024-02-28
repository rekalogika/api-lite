<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Book;

use ApiPlatform\Metadata\Operation;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<void,void>
 */
class BookRemoveProcessor extends AbstractProcessor
{
    public function __construct(
        private BookRepository $bookRepository,
        private EntityManagerInterface $entityManager
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
        $this->denyAccessUnlessGranted('remove', $book);

        // Remove the book
        $this->entityManager->remove($book);

        // Flush the change to the database
        $this->entityManager->flush();
    }
}
