<?php

namespace App\Service;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductService
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }

    public function createProduct(string $link): Product
    {
        $product = new Product();
        $product->setLink($link);
        $product->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }

    public function updateProduct(Product $product, string $link): Product
    {
        $product->setLink($link);
        $product->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->persist($product);
        $this->entityManager->flush();
        return $product;
    }
    public function deleteProduct(Product $product): bool
    {
        $this->entityManager->remove($product);
        $this->entityManager->flush();
        return true;
    }

    public function getProductById(int $id) : ?Product
    {
        return $this->entityManager->getRepository(Product::class)->find($id);
    }
}