<?php

namespace App\Libs\Auth;

use App\Libs\Interfaces\AuthenticatorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;

class Authenticator implements AuthenticatorInterface
{
    private UserProvider $userProvider;

    public function __construct(UserProvider $userProvider)
    {
        $this->userProvider = $userProvider;
    }
    
    public function authenticate(Request $request): ?UsernamePasswordToken
    {
        $username = $request->request->get('username');
        $password = $request->request->get('password');

        try {
            $user = $this->userProvider->loadUserByIdentifier($username);
        } catch (UserNotFoundException) {
            return null;
        }

        if ($user->getPassword() !== $password) {
            return null;
        }
        return new UsernamePasswordToken($user, 'main', $user->getRoles());
    }

    public function isAuthenticated(): bool
    {
        // check session user is exist
        return isset($_SESSION['user']);
    }

    public function onAuthenticationSuccess(Request $request, UsernamePasswordToken $token): Response
    {
        session_start();
        $_SESSION['user'] = serialize($token->getUser());
        
        // redirect to home
        return new RedirectResponse("/admin/home");
    }

    public function onAuthenticationFailed(Request $request, AuthenticationException $e): Response
    {
        // redirect to login
        return new RedirectResponse("/admin/login");
    }
}
