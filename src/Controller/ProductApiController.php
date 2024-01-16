<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductApiController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService
    )
    {
    }
    #[Route('/api/product/{id}', name: 'get_product', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $product = $this->productService->getProductById($id);

        if (!$product) {
            return $this->json([
                'message' => 'Product not found'
            ], 404);
        }

        $productDetail = $product->getProductDetail()->first();

        if (!$productDetail) {
            return $this->json([
                'message' => 'Product details not found'
            ], 404);
        }

        return $this->json([
            'title' => $productDetail->getTitle(),
            'description' => $productDetail->getDescription(),
            'price' => $productDetail->getPrice(),
            'discountPercentage' => $productDetail->getDiscountPercentage(),
            'rating' => $productDetail->getRating(),
            'stock' => $productDetail->getStock(),
            'brand' => $productDetail->getBrand(),
        ]);
    }
}
