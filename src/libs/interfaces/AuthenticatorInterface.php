<?php

namespace App\Libs\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

interface AuthenticatorInterface
{
    public function authenticate(Request $request): ?UsernamePasswordToken;
    public function isAuthenticated(): bool;
    public function onAuthenticationSuccess(Request $request, UsernamePasswordToken $token);
    public function onAuthenticationFailed(Request $request, AuthenticationException $e);
}