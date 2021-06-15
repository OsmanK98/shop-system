<?php

namespace App\Controller;

use App\Service\Product\ListProductsService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;

class ProductController extends AbstractFOSRestController
{
    private ListProductsService $listProductsService;

    public function __construct(ListProductsService $listProductsService)
    {
        $this->listProductsService = $listProductsService;
    }

    /**
     * @Rest\Get("/products")
     * @SWG\Get(
     *     summary="Get products list",
     *     tags={"Product"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="page",
     *         in="path",
     *         type="integer",
     *         description="Number of page"
     *     ),
     *     @SWG\Parameter(
     *         name="limit",
     *         in="path",
     *         type="integer",
     *         description="Limit of records"
     *     ),
     *      @SWG\Response(
     *         response=200,
     *         description="Returns products list")
     * )
     */
    public function index(Request $request): View
    {
        $products = $this->listProductsService->execute($request);
        return $this->view($products);
    }
}
