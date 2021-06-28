<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;

class CreateProductService
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function execute(Request $request): Void
    {
        $productData = json_decode($request->getContent(), true);
        $product = $this->prepareProductObject($productData);
        $this->productRepository->save($product);
    }

    private function prepareProductObject($productData): Product
    {
        $product = new Product();
        $product->setName($productData['name']);
        $product->setDescription($productData['description']);
        $product->setQuantity($productData['quantity']);
        $product->setSource($productData['source']);
        $product->setPhotoUrl($productData['photo_url']);
        $product->setPrice($productData['price']);
        return $product;
    }
}
