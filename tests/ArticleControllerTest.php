<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 21.11.18
 * Time: 15:09
 */

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ArticleControllerTest extends WebTestCase
{
    public function testRoutes()
    {
        $client = static::createClient();
        $client->request('GET', '/article');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}