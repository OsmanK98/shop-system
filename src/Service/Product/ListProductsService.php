<?php

namespace App\Service\Product;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ListProductsService
{
    private ProductRepository $productRepository;
    private PaginatorInterface $paginator;
    private array $products;

    public function __construct(ProductRepository $productRepository,
                                PaginatorInterface $paginator
    )
    {
        $this->productRepository = $productRepository;
        $this->paginator = $paginator;
    }

    public function execute(Request $request): iterable
    {
        $this->products = $this->productRepository->findAll();
        return $this->getPaginated($request);
    }

    private function getPaginated($request): iterable
    {
        return $this->paginator->paginate($this->products,
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', Product::PAGINATE_DEFAULT_LIMIT))
            ->getItems();
    }
}