<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\User\ReviewInputDto;
use App\ApiResource\User\ReviewDto;
use App\Entity\Review;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<ReviewInputDto,ReviewDto>
 */
class BookReviewCreateUnderBookIriProcessor extends AbstractProcessor
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
        $book = $this->bookRepository
            ->find($uriVariables['bookId'] ?? null)
            ?? throw new NotFoundException('Book not found');

        $this->denyAccessUnlessGranted('addReview', $book);

        $review = $this->map($data, Review::class);
        $book->addReview($review);

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return $this->map($review, ReviewDto::class);
    }
}
