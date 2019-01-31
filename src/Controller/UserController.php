<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 2019-01-31
 * Time: 14:29
 */

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserController
{
    /**
     * @Route("/users/me")
     *
     * @param Security $security
     *
     * @return JsonResponse
     */
    public function me(Security $security)
    {
        $user = $security->getUser();

        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException();
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'username' => $user->getUsername(),
                'roles' => $user->getRoles(),
            ],
        ]);
    }
}
