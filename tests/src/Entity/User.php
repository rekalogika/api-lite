<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
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
     * @param list<string> $roles
     */
    private function __construct(
        private string $username = 'user',
        private array $roles = ['ROLE_USER'],
    ) {
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
