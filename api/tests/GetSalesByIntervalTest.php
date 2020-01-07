<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

class GetSalesByIntervalTest extends ApiTestCase
{
    public function testOk(): void
    {
        static::createClient()->request('GET', '/land_value_claims/salesbyinterval', [
            'query' => [
                'interval' => 'day',
                'date_start' => '2017-01-03',
                'date_end' => '2019-02-24' 
            ]
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('content-type', 'application/json');
    }

    public function testIntervalIncorrect(): void
    {
        $response = static::createClient()->request('GET', '/land_value_claims/salesbyinterval', [
            'query' => [
                'interval' => 'hour',
                'date_start' => '2017-01-03',
                'date_end' => '2019-02-24' 
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
        assert(strpos($response->getContent(false), 'Illegal interval') !== false);
    }

    public function testDateStartIncorrect(): void
    {
        $response = static::createClient()->request('GET', '/land_value_claims/salesbyinterval', [
            'query' => [
                'interval' => 'day',
                'date_start' => '2017-01-abcd',
                'date_end' => '2019-02-24' 
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
        assert(strpos($response->getContent(false), 'Illegal date_start') !== false);
    }

    public function testDateEndIncorrect(): void
    {
        $response = static::createClient()->request('GET', '/land_value_claims/salesbyinterval', [
            'query' => [
                'interval' => 'day',
                'date_start' => '2017-01-03',
                'date_end' => '2019abcd24'
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
        assert(strpos($response->getContent(false), 'Illegal date_end') !== false);
    }

    public function testDateEndBeforeDateStart(): void
    {
        $response = static::createClient()->request('GET', '/land_value_claims/salesbyinterval', [
            'query' => [
                'interval' => 'day',
                'date_start' => '2019-02-24',
                'date_end' => '2017-01-23'  
            ]
        ]);

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
        assert(strpos($response->getContent(false), 'before') !== false);
    }

    public function testSalesByIntervalNoParameter(): void
    {
        static::createClient()->request('GET', '/land_value_claims/salesbyinterval');

        $this->assertResponseStatusCodeSame(400);
        $this->assertResponseHeaderSame('content-type', 'application/text');
    }
}
