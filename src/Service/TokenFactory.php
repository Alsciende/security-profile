<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Permission;
use App\Entity\Token;
use App\Exception\CannotGrantTokenException;
use App\UserAwareInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Creates Token instances.
 */
class TokenFactory
{
    /**
     * @var Security
     */
    private $security;

    /**
     * TokenFactory constructor.
     *
     * @param Security $security
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
     * @return Token
     *
     * @throws AccessDeniedException
     * @throws CannotGrantTokenException
     */
    public function createToken(string $id, array $permissions): Token
    {
        $user = $this->security->getUser();
        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException();
        }

        foreach ($permissions as $permission) {
            if (false === $this->security->isGranted($permission->getId())) {
                throw new CannotGrantTokenException($permission);
            }
        }

        while ($user instanceof UserAwareInterface) {
            $user = $user->getUser();
        }

        return new Token($id, $user, $permissions);
    }
}
