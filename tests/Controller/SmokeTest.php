<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SmokeTest extends WebTestCase{
    public function testApiDocUrlIsSuccessful():void{
        $client = self::createClient();
        $client->request(method:'GET',uri:'api/doc');
        self::assertResponseIsSuccessful();
    }

}