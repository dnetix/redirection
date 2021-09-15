<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Status;
use Tests\BaseTestCase;

class ServiceReverseTest extends BaseTestCase
{
    public function testItReversesATransaction()
    {
        $data = [
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'Aprobada',
                'date' => '2021-09-14T21:20:06-05:00',
            ],
            'payment' => [
                'status' => [
                    'status' => 'APPROVED',
                    'reason' => '00',
                    'message' => 'Aprobada',
                    'date' => '2021-09-14T21:20:06-05:00',
                ],
                'internalReference' => 1519102359,
                'paymentMethod' => 'master',
                'paymentMethodName' => 'Master',
                'issuerName' => 'Banco del Pacifico, S.A.',
                'amount' => [
                    'from' => [
                        'currency' => 'COP',
                        'total' => '2009000.00',
                    ],
                    'to' => [
                        'currency' => 'COP',
                        'total' => '2009000.00',
                    ],
                    'factor' => 1,
                ],
                'authorization' => '000000',
                'reference' => '800166551',
                'receipt' => 72406,
                'franchise' => 'RM_MC',
                'refunded' => false,
            ],
        ];
        $response = $this->getService()->reverse(1519097768);
        $this->assertEquals($data, $response->toArray());
    }

    public function testItHandlesAnErrorOnReverseCall()
    {
        $response = $this->getService()->reverse('');
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals(Status::ST_FAILED, $response->status()->status());
        $this->assertEmpty($response->payment());
    }
}
