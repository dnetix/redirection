<?php

namespace Tests\Messages;

use Dnetix\Redirection\Message\RedirectResponse;
use Tests\BaseTestCase;

class RedirectResponseTest extends BaseTestCase
{

    public function testRedirectionResponseWithoutStatus()
    {
        $carrierResponse = new RedirectResponse([
            'requestId' => rand(0, 100000),
            'processUrl' => 'http://localhost/payment/process',
        ]);

        $this->assertFalse($carrierResponse->isSuccessful());
    }

    public function testRedirectionResponseWithStatus()
    {
        $carrierResponse = new RedirectResponse([
            'requestId' => rand(0, 100000),
            'processUrl' => 'http://localhost/payment/process',
            'status' => [
                'status' => 'OK',
                'reason' => 2,
                'message' => 'Aprobada',
                'date' => '2019-03-10T12:36:36-05:00'
            ]
        ]);
        $this->assertTrue($carrierResponse->isSuccessful());
    }

}
