<?php

declare(strict_types=1);

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

abstract class AbstractUser implements UserInterface
{
    /**
     * @var Profile
     */
    private $profile;

    public function getRoles()
    {
        return $this->profile->getRoles();
    }
}
