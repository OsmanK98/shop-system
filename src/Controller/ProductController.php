<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\Product\CreateProductService;
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
    private CreateProductService $createProductService;

    public function __construct(ListProductsService $listProductsService,
                                CreateProductService $createProductService)
    {
        $this->listProductsService = $listProductsService;
        $this->createProductService = $createProductService;
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

    /**
     * @Rest\Get("/products/{id}")
     * @SWG\Get(
     *     summary="Show product",
     *     tags={"Product"},
     *     produces={"application/json"},
     *     @SWG\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         type="integer",
     *         description="Product ID"
     *          )
     *      )
     *      @SWG\Response(
     *         response=200,
     *         description="Return product")
     * )
     */
    public function show(Product $product): View
    {
        return $this->view($product, Response::HTTP_OK);
    }

    /**
     * @Rest\Post("/products")
     * @param Request $request
     * @return View
     * @SWG\Post(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"Product"},
     *     summary="Add new product",
     *     @SWG\Parameter(
     *         name="Authorization",
     *         in="header",
     *         required=true,
     *         type="string",
     *         default="Bearer TOKEN",
     *         description="Authorization"
     *     ),
     *     @SWG\Parameter(
     *       name="body",
     *       in="body",
     *       description="JSON product object",
     *       type="json",
     *       required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="name", type="string", example="Printer"),
     *              @SWG\Property(property="description", type="text", example="Model 4215K"),
     *              @SWG\Property(property="quantity", type="integer", example="10"),
     *              @SWG\Property(property="source", type="string", example="manual"),
     *              @SWG\Property(property="photo_url", type="text", example="shorturl.at/kEKS2"),
     *              @SWG\Property(property="price", type="float", example="299.99"),
     *          )
     *   )
     * )
     * @SWG\Response(
     *         response=204,
     *         description="Product was created",
     *     @SWG\Schema(
     *     @SWG\Property(property="product_id", type="integer", example="1"),
     * )
     *     ),
     * @SWG\Response(
     *         response=401,
     *         description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *     )
     */
    public function create(Request $request): View
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN', $this->getUser());
        $product = $this->createProductService->execute($request);
        if ($product instanceof Product) {
            return $this->view(['product_id' => $product->getId()], Response::HTTP_OK);
        }
        return $this->view(['error' => $product], Response::HTTP_BAD_REQUEST);
    }
}
