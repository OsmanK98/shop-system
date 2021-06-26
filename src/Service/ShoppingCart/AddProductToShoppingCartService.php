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
    private ?ShoppingCartDetails $shoppingCartDetails;
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
        if (!$this->isProductExistInShoppingCart()) {
            $shoppingCart = $this->prepareShoppingCartObject();
        }else
        {
            $shoppingCart = $this->updateShoppingCartObject();
            if(!$shoppingCart)
            {
                return $this->errors;
            }
        }

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

    private function isProductExistInShoppingCart(): bool
    {
        $this->shoppingCartDetails = $this->shoppingCartDetailsRepository->findOneBy(['product' => $this->product]);
        if ($this->shoppingCartDetails) {
            return true;
        }
        return false;
    }

    private function prepareShoppingCartObject(): ShoppingCartDetails
    {
        $shoppingCartDetails = new ShoppingCartDetails();
        $shoppingCartDetails->setProduct($this->product);
        $shoppingCartDetails->setQuantity($this->quantity);
        $shoppingCartDetails->setUser($this->getUser());
        return $shoppingCartDetails;
    }

    private function updateShoppingCartObject()
    {
        $sumQuantity=$this->quantity + $this->shoppingCartDetails->getQuantity();
        if(!$this->isQuantityAvailable($sumQuantity))
        {
            $this->errors = ([
                'error' => 'Product exist in shopping cart, but the amount given exceeds the total amount available.'
            ]);
            return false;
        }

        $this->shoppingCartDetails->setQuantity($this->quantity);
        $this->shoppingCartDetails->setUser($this->getUser());
        return $this->shoppingCartDetails;
    }
}
