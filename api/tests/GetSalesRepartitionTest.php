<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class GetSalesRepartitionTest extends ApiTestCase
{
    public function testOk(): void
    {
        static::createClient()->request('GET', '/land_value_claims/salesrepartition', [
            'query' => [
                'year' => 2015
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testIncorrect(): void
    {
        $response = static::createClient()->request('GET', '/land_value_claims/salesrepartition', [
            'query' => [
                'year' => "abcd"
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
        assert(strcmp($response->getContent(false), 'Bad Request') !== false);
    }

    public function testNoParameter(): void
    {
        static::createClient()->request('GET', '/land_value_claims/salesrepartition');

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
    }
}
