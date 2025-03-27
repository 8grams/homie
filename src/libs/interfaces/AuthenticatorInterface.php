<?php

namespace App\Libs\Interfaces;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Interface for authentication operations
 * 
 * This interface defines methods for:
 * - Authenticating users
 * - Checking authentication status
 * - Handling authentication success/failure
 */
interface AuthenticatorInterface
{
    /**
     * Attempt to authenticate a user from the request
     * 
     * @param Request $request The HTTP request
     * @return UsernamePasswordToken|null Authentication token if successful, null otherwise
     */
    public function authenticate(Request $request): ?UsernamePasswordToken;

    /**
     * Check if the current user is authenticated
     * 
     * @return bool True if user is authenticated, false otherwise
     */
    public function isAuthenticated(): bool;

    /**
     * Handle successful authentication
     * 
     * @param Request $request The HTTP request
     * @param UsernamePasswordToken $token The authentication token
     * @return Response|null Response to send to the client, or null to continue normal flow
     */
    public function onAuthenticationSuccess(Request $request, UsernamePasswordToken $token);

    /**
     * Handle failed authentication
     * 
     * @param Request $request The HTTP request
     * @param AuthenticationException $e The authentication exception
     * @return Response|null Response to send to the client, or null to continue normal flow
     */
    public function onAuthenticationFailed(Request $request, AuthenticationException $e);
}