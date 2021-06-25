<?php


namespace App\Service\Product;


use App\Controller\ProductController;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Utils\HandleError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateProductService
{
    private ProductRepository $productRepository;
    private ValidatorInterface $validator;
    private HandleError $handleError;

    public function __construct(ProductRepository $productRepository,
                                ValidatorInterface $validator,
                                HandleError $handleError)
    {
        $this->productRepository = $productRepository;
        $this->validator = $validator;
        $this->handleError = $handleError;
    }

    public function execute(Request $request)
    {
        $productData = json_decode($request->getContent(), true);
        $product = $this->prepareProductObject($productData);

        if (!$errors = $this->handleError->isValidatedObject($product)) {
            return $errors;
        }

        $this->productRepository->save($product);
        return $product;
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
