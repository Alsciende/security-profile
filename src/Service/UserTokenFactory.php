<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Permission;
use App\Entity\UserToken;
use App\Exception\CannotGrantTokenException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Creates UserToken instances.
 */
class UserTokenFactory
{
    /**
     * @var Security
     */
    private $security;

    /**
     * UserTokenFactory constructor.
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * Create a new token for a user and a set of permissions.
     *
     * @param string       $id
     * @param Permission[] $permissions
     *
     * @return UserToken
     *
     * @throws AccessDeniedException
     * @throws CannotGrantTokenException
     */
    public function createToken(string $id, array $permissions): UserToken
    {
        if (!$this->security->getUser() instanceof UserInterface) {
            throw new AccessDeniedException();
        }

        foreach ($permissions as $permission) {
            if (false === $this->security->isGranted($permission->getRole())) {
                throw new CannotGrantTokenException($permission);
            }
        }

        return new UserToken($id, $this->security->getUser(), $permissions);
    }
}
