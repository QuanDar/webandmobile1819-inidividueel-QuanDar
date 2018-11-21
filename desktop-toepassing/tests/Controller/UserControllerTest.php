<?php

namespace App\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends WebTestCase
{

    public function testUserController_GET_user(){
        $client = static::createClient();

        $client->request('GET', '/user');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testUserController_GET_userAdd(){
        $client = static::createClient();

        $client->request('GET', '/user/post/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testUserController_POST_userAdd()
    {
        $client = static::createClient();

        $client->request('POST', '/user/post/add');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUserController_GET_userUpdate(){
        $client = static::createClient();

        $client->request('GET', '/user/post/update');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testUserController_POST_userUpdate()
    {
        $client = static::createClient();

        $client->request('POST', '/user/post/update');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUserController_GET_userDelete(){
        $client = static::createClient();

        $client->request('GET', '/user/post/delete');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testUserController_POST_userDelete()
    {
        $client = static::createClient();

        $client->request('POST', '/user/post/delete');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testUserController_getRightPage(){
        $client = static::createClient();

        $crawler = $client->request('GET', '/user');

        $this->assertSame('User Controls', $crawler->filter('h1')->text());
    }

}