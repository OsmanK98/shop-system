<?php

namespace App\Service\ShoppingCart;

use App\Entity\Product;
use App\Entity\ShoppingCartDetails;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartDetailsRepository;
use App\Utils\HandleError;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\HttpFoundation\Request;

class AddProductToShoppingCartService extends AbstractFOSRestController
{
    private ProductRepository $productRepository;
    private ShoppingCartDetailsRepository $shoppingCartDetailsRepository;
    private HandleError $handleError;

    private ?Product $product;
    private int $quantity;
    private array $errors;

    public function __construct(ProductRepository $productRepository,
                                ShoppingCartDetailsRepository $shoppingCartDetailsRepository,
                                HandleError $handleError)
    {
        $this->productRepository = $productRepository;
        $this->shoppingCartDetailsRepository = $shoppingCartDetailsRepository;
        $this->handleError = $handleError;
    }

    public function execute(array $request)
    {
        if (!$this->isRequestValidated($request)) {
            return $this->errors;
        }
        $shoppingCart = $this->prepareShoppingCartObject();

        if (!$errors = $this->handleError->isValidatedObject($shoppingCart)) {
            return $errors;
        }
        $this->shoppingCartDetailsRepository->save($shoppingCart);
        return $shoppingCart;
    }

    private function isRequestValidated($request): bool
    {
        if (!$this->isProductExist($request['product_id'])) {
            return false;
        }
        if (!$this->isQuantityAvailable($request['quantity'])) {
            return false;
        }
        return true;
    }

    private function isProductExist(int $id): bool
    {
        $this->product = $this->productRepository->find($id);
        if (!$this->product) {
            $this->errors = (['error' => sprintf('Product with id %d not found', $id)]);
            return false;
        }
        return true;
    }

    private function isQuantityAvailable(int $quantity): bool
    {
        $productAvailableQuantity = $this->product->getQuantity();
        if ($productAvailableQuantity < $quantity) {
            $this->errors = (['error' => sprintf('Quantity %d not available. The available quantity is %d', $quantity, $productAvailableQuantity)]);
            return false;
        }
        $this->quantity = $quantity;
        return true;
    }

    private function prepareShoppingCartObject(): ShoppingCartDetails
    {
        $shoppingCartDetails = new ShoppingCartDetails();
        $shoppingCartDetails->setProduct($this->product);
        $shoppingCartDetails->setQuantity($this->quantity);
        $shoppingCartDetails->setUser($this->getUser());
        return $shoppingCartDetails;
    }
}
