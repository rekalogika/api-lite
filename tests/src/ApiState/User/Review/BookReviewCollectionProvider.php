<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\ReviewDto;
use App\Repository\BookRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<ReviewDto>
 */
class BookReviewCollectionProvider extends AbstractProvider
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
        // Gets the book entity from the repository
        $book = $this->bookRepository
            ->find($uriVariables['bookId'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Check for authorization
        $this->denyAccessUnlessGranted('getReviews', $book);

        // Get the reviews from the book entity
        $reviews = $book->getReviews();

        return $this->mapCollection(
            collection: $reviews,
            target: ReviewDto::class,
            operation: $operation,
            context: $context
        );
    }
}
