<?php

namespace App\Controller;

use App\Entity\User;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Swagger\Annotations as SWG;

class UserController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/users/{id}")
     * @param User $user
     * @return View
     * @SWG\Get(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     summary="Get existing user details",
     *     tags={"User"},
     * @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         type="string",
     *         default="Bearer TOKEN",
     *         description="Authorization"
     *     ),
     * @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer",
     *         description="User id"
     *     )
     * )
     * @SWG\Response(
     *         response=200,
     *         description="Returns user details",
     *          @Model(type=User::class)
     *     ),
     * @SWG\Response(
     *         response=401,
     *         description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *     ),
     * @SWG\Response(
     *         response=404,
     *         description="User not found",
     *     )
     */
    public function show(User $user): View
    {
        return $this->view($user, Response::HTTP_OK);
    }
}
