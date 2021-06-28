<?php

namespace App\Service\ShoppingCart;

use App\Entity\Product;
use App\Entity\ShoppingCartDetails;
use App\Repository\ProductRepository;
use App\Repository\ShoppingCartDetailsRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\Request;

class AddProductToShoppingCartService extends AbstractFOSRestController
{
    private ProductRepository $productRepository;
    private ShoppingCartDetailsRepository $shoppingCartDetailsRepository;

    private ?Product $product;
    private ?ShoppingCartDetails $shoppingCartDetails;
    private int $quantity;

    public function __construct(ProductRepository $productRepository,
                                ShoppingCartDetailsRepository $shoppingCartDetailsRepository)
    {
        $this->productRepository = $productRepository;
        $this->shoppingCartDetailsRepository = $shoppingCartDetailsRepository;
    }

    public function execute(array $request): void
    {
        if (!$this->isRequestValidated($request)) {
            throw new Exception("The input data is invalid");
        }
        if ($this->isProductExistInShoppingCart()) {
            $this->updateShoppingCartObject();
        } else {
            $this->prepareShoppingCartObject();
        }
        $this->shoppingCartDetailsRepository->save($this->shoppingCartDetails);
    }

    private function isRequestValidated(array $request): bool
    {
        return $this->isProductExist($request['product_id']) && $this->isQuantityAvailable($request['quantity']);
    }

    private function isProductExist(int $id): bool
    {
        $this->product = $this->productRepository->find($id);
        return $this->product instanceof Product;
    }

    private function isQuantityAvailable(int $quantity): bool
    {
        $productAvailableQuantity = $this->product->getQuantity();
        if ($productAvailableQuantity < $quantity) {
            throw new Exception("Product exist in shopping cart, but the amount given exceeds the total amount available.'");
        }
        $this->quantity = $quantity;
        return true;
    }

    private function isProductExistInShoppingCart(): bool
    {
        $this->shoppingCartDetails = $this->shoppingCartDetailsRepository->findOneBy(['product' => $this->product]);
        return $this->shoppingCartDetails instanceof ShoppingCartDetails;
    }

    private function prepareShoppingCartObject(): void
    {
        $this->shoppingCartDetails = new ShoppingCartDetails($this->getUser(), $this->product, $this->quantity);
    }

    private function updateShoppingCartObject(): void
    {
        $sumQuantity = $this->quantity + $this->shoppingCartDetails->getQuantity();
        $this->isQuantityAvailable($sumQuantity);
        $this->shoppingCartDetails->setQuantity($this->quantity);
    }
}
