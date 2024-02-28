<?php

declare(strict_types=1);

namespace App\ApiState\Admin\Review;

use ApiPlatform\Metadata\Operation;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Rekalogika\ApiLite\Exception\NotFoundException;
use Rekalogika\ApiLite\State\AbstractProcessor;

/**
 * @extends AbstractProcessor<void,void>
 */
class ReviewRemoveProcessor extends AbstractProcessor
{
    public function __construct(
        private ReviewRepository $reviewRepository,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function process(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ) {
        $review = $this->reviewRepository
            ->find($uriVariables['id'] ?? null)
            ?? throw new NotFoundException('Review not found');

        $this->denyAccessUnlessGranted('remove', $review);

        $this->entityManager->remove($review);
        $this->entityManager->flush();
    }
}
