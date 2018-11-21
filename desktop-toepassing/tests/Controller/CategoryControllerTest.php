<?php

namespace App\Test\Controller;


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryControllerTest extends WebTestCase
{
    public function testCategoryController_GET(){
        $client = static::createClient();

        $client->request('GET', '/category/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testCategoryController_POST(){
        $client = static::createClient();

        $client->request('GET', '/category/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testCategoryController_POST_2()
    {
        $client = static::createClient();

        $client->request('POST', '/user/post/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCategoryController_GET_2()
    {
        $client = static::createClient();

        $client->request('GET', '/user/post/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCategoryController_getRightPage(){
        $client = static::createClient();

        $crawler = $client->request('GET', '/category/add');

        $this->assertSame('Add a Category', $crawler->filter('h2')->text());
    }
}