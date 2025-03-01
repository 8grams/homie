<?php 

namespace App\Libs\Auth;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

interface AbstractUser extends UserInterface, PasswordAuthenticatedUserInterface
{
    
}
