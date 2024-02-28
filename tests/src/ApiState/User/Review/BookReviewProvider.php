<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\ReviewDto;
use App\Repository\BookRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;
use Symfony\Component\Uid\Uuid;

/**
 * @extends AbstractProvider<ReviewDto>
 */
class BookReviewProvider extends AbstractProvider
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
        // get book

        $book = $this->bookRepository
            ->find($uriVariables['bookId'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // get review

        $reviewId = $uriVariables['id']
            ?? throw new NotFoundException('Review not found');

        if (!$reviewId instanceof Uuid) {
            throw new \InvalidArgumentException('Invalid reviewId');
        }

        $review = $book->getReviews()->get($reviewId->toBinary())
            ?? throw new NotFoundException('Review not found');

        $this->denyAccessUnlessGranted('view', $review);

        return $this->map($review, ReviewDto::class);
    }
}
