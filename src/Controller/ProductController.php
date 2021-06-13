<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;

class ProductController extends AbstractFOSRestController
{
    /**
     * @Rest\Get("/products")
     * @return Response
     * @SWG\Get(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     summary="Get tickets list",
     *     tags={"Product"})
     * @SWG\Response(
     *         response=200,
     *         description="Returns products list")
     */

    public function index(): Response
    {
        $data= [
            'test'=>'test',
        ];
        $view = $this->view($data, 200);
        return $this->handleView($view);
    }
}
