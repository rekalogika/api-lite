<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\Admin\ReviewDto;
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
        // Gets the book from the database
        $book = $this->bookRepository
            ->find($uriVariables['bookId'] ?? null)
            ?? throw new NotFoundException('Book not found');

        // Check for authorization
        $this->denyAccessUnlessGranted('getReviews', $book);

        // Get the collection of reviews we want to show. This will get us a
        // Doctrine collection of Review entities.
        $reviews = $book->getReviews();

        // Map the collection of reviews to a collection of output DTO, and
        // return them.
        return $this->mapCollection(
            collection: $reviews,
            target: ReviewDto::class,
            operation: $operation,
            context: $context
        );
    }
}
