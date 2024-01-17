<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

#[Route('/admin')]
class AdminController extends AbstractController
{
    public function __construct(
        private readonly Environment $twig,
        private readonly ProductService $productService
    )
    {
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    #[Route('/product/create', name: 'create_product', methods: ['POST'])]
    public function createProduct(Request $request): Response
    {
        $product = $this->productService->createProduct($request->get('link'));

        return new Response($this->twig->render('admin/create.html.twig', [
            'product' => $product,
        ]));
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    #[Route('/product/update/{id}', name: 'update_product', methods: ['PUT'])]
    public function updateProduct(Request $request, Product $product): Response
    {
        $product = $this->productService->updateProduct($product, $request->get('link'));
        return new Response($this->twig->render('admin/update.html.twig', [
            'product' => $product,
        ]));
    }

    #[Route('/product/delete/{id}', name: 'delete_product', methods: ['DELETE'])]
    public function deleteProduct(Product $product): Response
    {
        $this->productService->deleteProduct($product);
        return new Response('Product deleted successfully');
    }

    #[Route('/product/{id}', name: 'get_product', methods: ['GET'])]
    public function getProduct(Product $product): Response
    {
        $productDetail = $product->getProductDetail();

        if (!$productDetail) {
            throw $this->createNotFoundException('No product detail found for id ' . $product->getId());
        }

        return new Response($this->twig->render('admin/product.html.twig', [
            'productDetail' => $productDetail,
        ]));
    }
}
