<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\ReviewDto;
use App\Repository\ReviewRepository;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<ReviewDto>
 */
class ReviewCollectionProvider extends AbstractProvider
{
    public function __construct(
        private ReviewRepository $reviewRepository
    ) {
    }

    public function provide(
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object|array|null {
        $this->denyAccessUnlessGranted('view', $this->reviewRepository);

        return $this->mapCollection(
            collection: $this->reviewRepository,
            target: ReviewDto::class,
            operation: $operation,
            context: $context
        );
    }
}
