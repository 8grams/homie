<?php

use Symfony\Component\Security\Core\Exception\AuthenticationException;

if ($this->request->getMethod() === 'POST') {
    $token = $this->authenticator->authenticate($this->request);

    if ($token) {
        return $this->authenticator->onAuthenticationSuccess($this->request, $token);
    }

    return $this->authenticator->onAuthenticationFailed($this->request, new AuthenticationException("Invalid credentials."));
}

?>

<form method="post" action="/admin/login">
    <input type="text" name="username" placeholder="Username" required>
    <input type="password" name="password" placeholder="Password" required>
    <button type="submit">Login</button>
</form>