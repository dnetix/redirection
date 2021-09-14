<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Tests\BaseTestCase;
use Tests\Mocks\RestCarrierMock;

class ServiceRequestTest extends BaseTestCase
{
    public function baseRequest(array $overrides = []): array
    {
        return [
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
        ];
    }

    public function testItHandlesTheHeaders()
    {
        $this->getService([
            'headers' => [
                'X-TEST-HEADER' => 'SOME_VALUE',
            ],
        ])->request($this->baseRequest());

        $this->assertArrayHasKey('X-TEST-HEADER', RestCarrierMock::instance()->headers());
    }

    public function testItFailsIfTheRequestIsNotAccepted()
    {
        $this->expectException(PlacetoPayException::class);
        $this->getService()->request((object)$this->baseRequest());
    }

    public function testItCreatesABasicSession()
    {
        $response = $this->getService()->request($this->baseRequest());

        $this->assertEquals(Status::ST_OK, $response->status()->status());
        $this->assertEquals('120000', $response->requestId());
        $this->assertNotEmpty($response->processUrl());
    }
}
