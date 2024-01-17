<?php
namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testGetProduct()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/product/1');

        $this->assertResponseStatusCodeSame(200);

        $responseContent = $client->getResponse()->getContent();

        $this->assertNotEmpty($responseContent, "Response content is empty");

        $productData = json_decode($responseContent, true);

        $this->assertIsArray($productData, "Decoding JSON failed");

        $this->assertArrayHasKey('title', $productData);
        $this->assertArrayHasKey('description', $productData);
        $this->assertArrayHasKey('price', $productData);
        $this->assertArrayHasKey('discountPercentage', $productData);
        $this->assertArrayHasKey('rating', $productData);
        $this->assertArrayHasKey('stock', $productData);
        $this->assertArrayHasKey('brand', $productData);
        $this->assertEquals('iPhone 9', $productData['title']);
        $this->assertEquals('Apple', $productData['brand']);
    }
}
