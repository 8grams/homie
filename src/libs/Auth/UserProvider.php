<?php

namespace App\Libs\Auth;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class UserProvider implements UserProviderInterface
{
    private array $users = [
        'admin' => ['password' => 'Password01', 'roles' => ['ROLE_ADMIN']]
    ];

    public function loadUserByIdentifier(string $username): UserInterface
    {
        if (!isset($this->users[$username])) {
            throw new UserNotFoundException("User not found.");
        }

        return new User($username, $this->users[$username]['password'], $this->users[$username]['roles']);
    }

    public function refreshUser(UserInterface $user): UserInterface { return $user; }
    public function supportsClass(string $class): bool { return $class === User::class; }
}
