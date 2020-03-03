<?php

namespace Tests\Validators;

use Dnetix\Redirection\Entities\Amount;
use Dnetix\Redirection\Entities\Payment;
use Dnetix\Redirection\Exceptions\EntityValidationFailException;
use Tests\BaseTestCase;

class PaymentValidatorTest extends BaseTestCase
{
    public function testItPassesWhenAllOk()
    {
        $data = [
            'reference' => '1234567890',
            'amount' => [
                'currency' => 'COP',
                'total' => 1000
            ],
            'allowPartial' => true,
            'subscribe' => false,
        ];
        $payment = new Payment($data);
        $this->assertEquals($data['reference'], $payment->reference());
        $this->assertEquals($data['allowPartial'], $payment->allowPartial());

        $this->assertEquals($data, $payment->toArray());
    }

    public function testItAllowsEmptyInstantiation()
    {
        $payment = new Payment();
        $this->assertNull($payment->amount());
    }

    public function testItReceivesAllTheEntities()
    {
        $data = [
            'reference' => '1234567890',
            'description' => 'Testing payment',
            'amount' => [
                'currency' => 'COP',
                'total' => 1000
            ],
            'recurring' => [
                'periodicity' => 'D',
                'interval' => 4,
                'nextPayment' => '2020-01-01',
                'maxPeriods' => 4,
                'dueDate' => '2022-01-01'
            ],
            'shipping' => [
                'name' => 'James',
                'email' => 'james@example.com',
                'address' => [
                    'street' => '706 Evergreen',
                    'city' => 'Villa de Nuestra Señora de la Candelaria de Medellín',
                    'country' => 'CO'
                ]
            ],
            'fields' => [
                [
                    'displayOn' => 'both',
                    'keyword' => 'testing',
                    'value' => 'Testing value'
                ]
            ],
            'allowPartial' => true,
            'subscribe' => false,
        ];

        $payment = new Payment($data);
        $this->assertEquals($data['reference'], $payment->reference());
        $this->assertEquals($data['amount']['total'], $payment->amount()->total());
        $this->assertEquals($data['recurring']['periodicity'], $payment->recurring()->periodicity());
        $this->assertEquals($data['shipping']['name'], $payment->shipping()->name());
        $this->assertEquals($data['shipping']['address']['street'], $payment->shipping()->address()->street());

        $this->assertEquals($data, $payment->toArray());
    }

    public function testItFailWhenDescriptionItsInvalid()
    {
        $data = [
            'reference' => '1234567890',
            'description' => '<script> </script>',
            'amount' => [
                'currency' => 'COP',
                'total' => 1000
            ],
            'allowPartial' => true,
            'subscribe' => false,
        ];
        try {
            (new Payment($data))->isValid($fields, false);
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['description'], $e->fields());
            $this->assertEquals('Payment', $e->from());
        }
    }

    public function testItPassesWhenDescriptionOk()
    {
        $data = [
            'reference' => '1234567890',
            'description' => 'Pago de prueba para la factura #2321 con ref. 43242342-3424_32. ($50.000) 25/feb',
            'amount' => [
                'currency' => 'COP',
                'total' => 1000
            ],
            'allowPartial' => true,
            'subscribe' => false,
        ];
        $payment = new Payment($data);
        $payment->isValid($fields, false);
        $this->assertEquals($data['reference'], $payment->reference());
        $this->assertEquals($data['description'], $payment->description());
        $this->assertEquals(new Amount($data['amount']), $payment->amount());

        $this->assertEquals($data, $payment->toArray());
    }
}
