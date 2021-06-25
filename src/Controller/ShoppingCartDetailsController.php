<?php

namespace App\Controller;

use App\Entity\ShoppingCartDetails;
use App\Service\Product\CreateProductService;
use App\Service\Product\ListProductsService;
use App\Service\ShoppingCart\AddProductToShoppingCartService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Swagger\Annotations as SWG;

class ShoppingCartDetailsController extends AbstractFOSRestController
{
    private AddProductToShoppingCartService $addProductToShoppingCartService;

    public function __construct(AddProductToShoppingCartService $addProductToShoppingCartService)
    {
        $this->addProductToShoppingCartService = $addProductToShoppingCartService;
    }

    /**
     * @Rest\Post("/shopping-cart-details")
     * @param Request $request
     * @return View
     * @SWG\Post(
     *     consumes={"application/json"},
     *     produces={"application/json"},
     *     tags={"ShoppingCart"},
     *     summary="Add new product to shopping cart",
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
     *       description="JSON product to shopping cart object",
     *       type="json",
     *       required=true,
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(property="product_id", type="integer", example="1"),
     *              @SWG\Property(property="quantity", type="integer", example="4"),
     *            )
     *          )
     *         )
     * @SWG\Response(
     *         response=204,
     *         description="Product was added to shopping cart",
     *              @SWG\Schema(
     *              @SWG\Property(property="product_id", type="integer", example="1"),
     *          )
     *     ),
     * @SWG\Response(
     *         response=401,
     *         description="Expired JWT Token | JWT Token not found | Invalid JWT Token",
     *     )
     */
    public function add(Request $request): View
    {
        $product = json_decode($request->getContent(), true);
        $shoppingCartDetails = $this->addProductToShoppingCartService->execute($product);
        if ($shoppingCartDetails instanceof ShoppingCartDetails) {
            return $this->view(['Product added to shopping cart'], Response::HTTP_OK);
        }
        return $this->view(['error' => $shoppingCartDetails], Response::HTTP_BAD_REQUEST);
    }

}
