<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Status;
use Tests\BaseTestCase;
use Tests\Mocks\RestCarrierMock;

class ServiceTest extends BaseTestCase
{
    public function testItHandlesTheHeaders()
    {
        $this->getService([
            'headers' => [
                'X-TEST-HEADER' => 'SOME_VALUE',
            ],
        ])->request([
            'payment' => [
                'reference' => 'TEST_20210913_120000',
                'amount' => [
                    'total' => 12844,
                    'currency' => 'COP',
                ],
            ],
            'returnUrl' => 'https://dnetix.co/ping/example',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
        ]);

        $this->assertArrayHasKey('X-TEST-HEADER', RestCarrierMock::instance()->headers());
    }

    public function testItCreatesABasicSession()
    {
        $response = $this->getService()->request([
            'payment' => [
                'reference' => 'TEST_20210913_120000',
                'amount' => [
                    'total' => 12844,
                    'currency' => 'COP',
                ],
            ],
            'returnUrl' => 'https://dnetix.co/ping/example',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
        ]);

        $this->assertEquals(Status::ST_OK, $response->status()->status());
        $this->assertEquals('120000', $response->requestId());
        $this->assertNotEmpty($response->processUrl());
    }
}
