<?php

namespace App\Libs\Auth;

use App\Libs\Auth\AbstractUser;

class User implements AbstractUser
{
    private string $username;
    private string $password;
    private array $roles;

    public function __construct(
        string $username, 
        string $password, 
        array $roles = ['ROLE_ADMIN'])
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
