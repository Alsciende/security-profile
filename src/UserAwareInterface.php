<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 2019-01-31
 * Time: 16:56
 */

namespace App;

use Symfony\Component\Security\Core\User\UserInterface;

interface UserAwareInterface
{
    public function getUser(): UserInterface;
}
