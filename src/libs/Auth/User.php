<?php
// src/Libs/User.php

namespace App\Libs\Auth;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    private string $username;
    private string $password;
    private array $roles;

    public function __construct(string $username, string $password, array $roles = ['ROLE_USER'])
    {
        $this->username = $username;
        $this->password = $password;
        $this->roles = $roles;
    }

    public function getRoles(): array { return $this->roles; }
    public function getPassword(): ?string { return $this->password; }
    public function getSalt(): ?string { return null; }
    public function getUserIdentifier(): string { return $this->username; }
    public function eraseCredentials(): void {}
}
