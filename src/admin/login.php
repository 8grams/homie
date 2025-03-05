<?php

use Symfony\Component\Security\Core\Exception\AuthenticationException;

if ($this->request->getMethod() === 'POST') {
    $token = $this->authenticator->authenticate($this->request);

    if ($token) {
        $this->authenticator->onAuthenticationSuccess($this->request, $token);    
    } else {
        $this->authenticator->onAuthenticationFailed($this->request, new AuthenticationException("Invalid credentials."));
    }
    header("Refresh:0");
}

?>

<form method="post" action="/admin/login">
    <input class="input" type="text" name="username" placeholder="Username" required>
    <input class="input" type="password" name="password" placeholder="Password" required>
    <button class="btn btn-primary" type="submit">Login</button>
</form>