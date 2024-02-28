<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\Admin\ReviewDto;
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
        // Gets the book from the database
        $book = $this->bookRepository
            ->find($uriVariables['bookId'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Gets the review ID from the URI variables
        $reviewId = $uriVariables['id']
            ?? throw new NotFoundException('Review not found');

        // Check the type of the review ID because the next step demands that
        // the ID is in UUID format. You don't need this check if you are using
        // a plain integer or string ID.
        if (!$reviewId instanceof Uuid) {
            throw new \InvalidArgumentException('Invalid reviewId');
        }

        // Get the review from the book's collection of reviews, using the
        // review ID.
        $review = $book->getReviews()->get($reviewId->toBinary())
            ?? throw new NotFoundException('Review not found');

        // Check for authorization
        $this->denyAccessUnlessGranted('view', $review);

        // Map the Review to the output DTO, and return it.
        return $this->map($review, ReviewDto::class);
    }
}
