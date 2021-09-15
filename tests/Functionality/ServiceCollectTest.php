<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\Transaction;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Tests\BaseTestCase;

class ServiceCollectTest extends BaseTestCase
{
    public function testItHandlesAnApprovedCollect()
    {
        $response = $this->getService()->collect($this->baseRequest([
            'instrument' => [
                'token' => [
                    'token' => 'cbdfb8e3304270a8ef107eeb19729f397a1fe1e045bf2e704e4f62c5242e91b6',
                    'installments' => '3',
                ],
            ],
        ]));

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($response->requestId());
        $this->assertTrue($response->status()->isApproved());
        $this->assertInstanceOf(Transaction::class, $response->lastApprovedTransaction());
    }

    public function testItHandlesABadCollectRequest()
    {
        $response = $this->getService()->collect($this->baseRequest([
            'payment' => [
                'reference' => 'PENDING',
            ],
            'instrument' => [
                'token' => [
                    'token' => 'cbdfb8e3304270a8ef107eeb19729f397a1fe1e045bf2e704e4f62c5242e91b6',
                    'installments' => '3',
                ],
            ],
        ]));

        $this->assertTrue($response->isSuccessful());
        $this->assertNotEmpty($response->requestId());
        $this->assertEquals(Status::ST_PENDING, $response->status()->status());
    }

    public function testItThrowsExceptionWhenBadArgumentProvided()
    {
        $this->expectException(PlacetoPayException::class);
        $this->getService()->collect((object)$this->baseRequest([
            'instrument' => [
                'token' => [
                    'token' => 'cbdfb8e3304270a8ef107eeb19729f397a1fe1e045bf2e704e4f62c5242e91b6',
                    'installments' => '3',
                ],
            ],
        ]));
    }
}
