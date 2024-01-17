<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    )
    {
    }
    public function load(ObjectManager $manager): void
    {
        $this->entityManager->getConnection()->exec('ALTER TABLE product AUTO_INCREMENT = 1');
        $this->entityManager->getConnection()->exec('ALTER TABLE product_detail AUTO_INCREMENT = 1');
        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product->setLink('https://dummyjson.com/products/'.$i);
            $product->setCreatedAt(new \DateTimeImmutable());

            $manager->persist($product);
        }
        $manager->flush();
    }
}
