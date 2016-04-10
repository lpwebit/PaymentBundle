<?php

namespace LpWeb\PaymentBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PaymentControllerTest extends WebTestCase
{
    public function testProcess()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/payment/process');

        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
