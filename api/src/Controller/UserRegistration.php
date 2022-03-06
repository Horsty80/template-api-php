<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class UserRegistration extends AbstractController
{
    public function __invoke(User $data): User
    {
        return $data;
    }
}
