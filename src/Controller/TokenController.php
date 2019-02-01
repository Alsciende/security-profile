<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 2019-01-31
 * Time: 16:34
 */

namespace App\Controller;

use App\Entity\Permission;
use App\Service\TokenFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenController
{
    /**
     * @Route(path="/tokens", methods={"POST"})
     *
     * @param Request                $request
     * @param Security               $security
     * @param EntityManagerInterface $manager
     * @param TokenFactory           $factory
     *
     * @return JsonResponse
     *
     * @throws \App\Exception\CannotGrantTokenException
     */
    public function post(Request $request, Security $security, EntityManagerInterface $manager, TokenFactory $factory)
    {
        $user = $security->getUser();
        if (!$user instanceof UserInterface) {
            throw new AccessDeniedException();
        }

        $message = json_decode((string) $request->getContent(), true);

        $permissions = [];
        foreach ($message['roles'] as $role) {
            $permission = $manager->getRepository(Permission::class)->find($role);
            if (is_null($permission)) {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'no_such_role',
                ]);
            }

            $permissions[] = $permission;
        }

        try {
            $token = $factory->createToken('abcd', $permissions);
            $manager->persist($token);
            $manager->flush();
        } catch (\Exception $exception) {
            return new JsonResponse([
                'success' => false,
                'message' => $exception->getMessage(),
            ]);
        }

        return new JsonResponse([
            'success' => true,
            'data' => [
                'id' => $token->getId(),
                'roles' => $token->getRoles(),
            ],
        ]);
    }
}
