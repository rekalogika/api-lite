<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Review;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string,Review>
 */
final class ReviewVoter extends Voter
{
    #[\Override]
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof Review;
    }

    #[\Override]
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;
    }
}
