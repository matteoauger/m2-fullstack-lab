<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class GetMeanPricesTests extends ApiTestCase
{
    public function testOk(): void
    {
        static::createClient()->request('GET', '/land_value_claims/meanprices');

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }
}
