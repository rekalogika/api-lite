<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\User\ReviewInputDtoWithBook;
use App\ApiResource\User\ReviewDto;
use App\Entity\Book;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<ReviewInputDtoWithBook,ReviewDto>
 */
class ReviewCreateAndAddReviewToBookProcessor extends AbstractProcessor
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        // We do not to use the mapper here to demonstrate interacting with
        // domain entities directly. In real-world applications, the domain can
        // necessitates you to use beyond the getters and setters, and using the
        // mapper would have been impractical.

        // Instantiate Review using the data from the input DTO
        $review = new Review();
        $review->setBody($data->body);
        $review->setRating($data->rating);

        // Get the Book entity by reverse-mapping the BookDto we have in the
        // input
        $bookDto = $data->book;
        $book = $this->map($bookDto, Book::class);

        // Check for authorization, and execute the action.
        $this->denyAccessUnlessGranted('addReview', $review);
        $book->addReview($review);

        // Persist the entity and flush the changes
        $this->entityManager->persist($review);
        $this->entityManager->flush();

        // Map the resulting Review to the output DTO, and return it.
        return $this->map($review, ReviewDto::class);
    }
}
