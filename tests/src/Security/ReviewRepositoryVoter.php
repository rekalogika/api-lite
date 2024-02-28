<?php

declare(strict_types=1);

namespace App\Security;

use App\Repository\ReviewRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @extends Voter<string,ReviewRepository>
 */
class ReviewRepositoryVoter extends Voter
{
    protected function supports(string $attribute, mixed $subject): bool
    {
        return $subject instanceof ReviewRepository;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        return true;
    }
}
