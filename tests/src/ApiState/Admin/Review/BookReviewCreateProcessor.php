<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\Admin\ReviewInputDto;
use App\ApiResource\Admin\ReviewDto;
use App\Entity\Review;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<ReviewInputDto,ReviewDto>
 * @deprecated
 */
class BookReviewCreateProcessor extends AbstractProcessor
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
