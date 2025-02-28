<?php

namespace App\Libs;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Libs\User;

class Authenticator
{
    public function authenticate(string $username, string $password, array $users): ?UserInterface
    {
        if (!isset($users[$username]) || $users[$username] !== $password) {
            return null;
        }

        $user = new User($username, $password, ['ROLE_USER']);

        // create session

        return $user;
    }

    public function isAuthenticated(): bool
    {
        // check session
        return true;
    }
}
