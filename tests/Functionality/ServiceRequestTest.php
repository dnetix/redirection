<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Dnetix\Redirection\Exceptions\PlacetoPayServiceException;
use Tests\BaseTestCase;
use Tests\Mocks\RestCarrierMock;

class ServiceRequestTest extends BaseTestCase
{
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
        $this->assertTrue($response->status()->isSuccessful());
        $this->assertEquals('120000', $response->toArray()['requestId']);
        $this->assertEquals('120000', $response->requestId());
        $this->assertNotEmpty($response->processUrl());
    }

    public function testItHandlesCorrectlyAnExceptionThrown()
    {
        $this->expectException(PlacetoPayServiceException::class);
        $this->getService()->request($this->baseRequest([
            'payment' => [
                'reference' => 'MAKE_EXCEPTION',
            ],
        ]));
    }

    public function testItHandlesASubscriptionRequest()
    {
        $response = $this->getService()->request($this->baseRequest([
            'payment' => null,
            'subscription' => [
                'reference' => 'SOME_REFERENCE',
                'description' => 'Testing subscription',
                'fields' => [
                    [
                        'keyword' => 'no_empty',
                        'value' => 'no_empty_value',
                        'displayOn' => 'none',
                    ],
                ],
            ],
        ]));
        $this->assertTrue($response->status()->isSuccessful());
    }
}
