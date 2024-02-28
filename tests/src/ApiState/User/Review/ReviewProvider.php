<?php

declare(strict_types=1);

namespace App\ApiState\User\Review;

use ApiPlatform\Metadata\Operation;
use App\ApiResource\User\ReviewDto;
use App\Repository\ReviewRepository;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProvider;

/**
 * @extends AbstractProvider<ReviewDto>
 */
class ReviewProvider extends AbstractProvider
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
        $review = $this->reviewRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Book not found');

        $this->denyAccessUnlessGranted('view', $review);

        return $this->map($review, ReviewDto::class);
    }
}
