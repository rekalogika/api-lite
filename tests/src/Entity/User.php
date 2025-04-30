<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

final class User implements UserInterface
{
    public static function user(): self
    {
        return new self('user', ['ROLE_USER']);
    }

    public static function admin(): self
    {
        return new self('admin', ['ROLE_USER', 'ROLE_ADMIN']);
    }

    /**
     * @param non-empty-string $username
     * @param list<string> $roles
     */
    private function __construct(
        private string $username = 'user',
        private array $roles = ['ROLE_USER'],
    ) {
    }

    #[\Override]
    public function getRoles(): array
    {
        return $this->roles;
    }

    #[\Override]
    public function eraseCredentials(): void
    {
    }

    #[\Override]
    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
