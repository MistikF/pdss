<?php

namespace App\Command;

use App\Entity\ProductDetail;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;

class UpdateProductDataCommand extends Command
{
    protected static $defaultName = 'products:update_data';
    private $client;
    private $entityManager;

    public function __construct(HttpClientInterface $client, EntityManagerInterface $entityManager)
    {
        $this->client = $client;
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Updates product data from a remote API');
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $products = $this->entityManager->getRepository(Product::class)->findAll();
        $updatedCount = 0;

        foreach ($products as $product) {
            $response = $this->client->request('GET', 'https://dummyjson.com/products/'.$product->getId());

            if ($response->getStatusCode() === 200) {
                $data = $response->toArray();

                // Получаем объект ProductDetail из Product
                $productDetail = $product->getProductDetail()->first();

                // Если у продукта нет связанного ProductDetail, создаем его
                if (!$productDetail) {
                    $productDetail = new ProductDetail();
                    $productDetail->setProduct($product);
                    $product->addProductDetail($productDetail);
                }

                // Обновляем данные продукта с помощью полученных данных
                $productDetail->setTitle($data['title']);
                $productDetail->setDescription($data['description']);
                $productDetail->setPrice($data['price']);
                $productDetail->setDiscountPercentage($data['discountPercentage']);
                $productDetail->setRating($data['rating']);
                $productDetail->setStock($data['stock']);
                $productDetail->setBrand($data['brand']);
                $productDetail->setCategory($data['category']);
                $productDetail->setThumbnail($data['thumbnail']);
                $productDetail->setProductId($data['id']);

                $this->entityManager->persist($productDetail);

                // Выводим информацию о каждом обновленном продукте
                $io->writeln("{$data['title']}: {$data['price']}, {$data['rating']}");

                $updatedCount++;
            }
        }


        $this->entityManager->flush();

        // Выводим общее количество обновленных строк
        $io->writeln("Total: {$updatedCount}");
        $io->writeln("=========================");

        return Command::SUCCESS;
    }
}
