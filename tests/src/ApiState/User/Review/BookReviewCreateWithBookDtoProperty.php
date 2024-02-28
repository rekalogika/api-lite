<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiInput\User\ReviewInputDtoWithBookDtoPropertyWritableLinkTrue;
use App\ApiResource\User\ReviewDto;
use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<ReviewInputDtoWithBookDtoPropertyWritableLinkTrue,ReviewDto>
 */
class BookReviewCreateWithBookDtoProperty extends AbstractProcessor
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
        $review = $this->map($data, Review::class);

        $this->entityManager->persist($review);
        $this->entityManager->flush();

        return $this->map($review, ReviewDto::class);
    }
}
