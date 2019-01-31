<?php

declare(strict_types=1);

namespace App\Entity;

use App\GetRolesTrait;
use Symfony\Component\Security\Core\User\UserInterface;

class UserToken implements UserInterface
{
    use GetRolesTrait;

    /**
     * @var string
     */
    private $id;

    /**
     * @var UserInterface
     */
    private $user;

    /**
     * @var Permission[]
     */
    private $permissions;

    /**
     * UserToken constructor.
     *
     * @param string        $id
     * @param UserInterface $user
     * @param Permission[]  $permissions
     */
    public function __construct(string $id, UserInterface $user, array $permissions)
    {
        $this->id = $id;
        $this->user = $user;
        $this->permissions = $permissions;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUser(): UserInterface
    {
        return $this->user;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }

    public function getPassword()
    {
        return $this->user->getPassword();
    }

    public function getSalt()
    {
        return $this->user->getSalt();
    }

    public function getUsername()
    {
        return $this->user->getUsername();
    }

    public function eraseCredentials()
    {
        return $this->user->eraseCredentials();
    }
}
