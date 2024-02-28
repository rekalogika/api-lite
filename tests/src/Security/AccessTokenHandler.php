<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        return match ($accessToken) {
            'user' => new UserBadge(
                'user',
                fn (string $userIdentifier) => User::user()
            ),
            'admin' => new UserBadge(
                'admin',
                fn (string $userIdentifier) => User::admin()
            ),
            default => throw new BadCredentialsException('Invalid credentials.'),
        };
    }
}
