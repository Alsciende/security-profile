<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: cedric
 * Date: 2019-02-01
 * Time: 10:02
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TestController is used to test the user role.
 * It does nothing but its routes are restricted to certain roles.
 */
class TestController
{
    /**
     * @Route(path="/view",methods={"GET"})
     * @IsGranted("ROLE_VIEW")
     *
     * @return JsonResponse
     */
    public function view()
    {
        return new JsonResponse([
            'success' => true,
        ]);
    }

    /**
     * @Route(path="/edit",methods={"GET"})
     * @IsGranted("ROLE_EDIT")
     *
     * @return JsonResponse
     */
    public function edit()
    {
        return new JsonResponse([
            'success' => true,
        ]);
    }
}
