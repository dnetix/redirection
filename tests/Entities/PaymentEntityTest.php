<?php

namespace Tests\Entities;

use Dnetix\Redirection\Entities\Payment;
use Tests\BaseTestCase;

class PaymentEntityTest extends BaseTestCase
{
    public function testItParsesTheDataCorrectly()
    {
        $data = [
            'reference' => 'required',
            'amount' => [
                'total' => 10000,
                'currency' => 'COP',
            ],
            'fields' => [
                [
                    'keyword' => 'no_empty',
                    'value' => 'no_empty_value',
                    'displayOn' => 'none',
                ],
            ],
            'items' => [
                [
                    'sku' => '1234',
                    'name' => 'Testing1',
                ],
                [
                    'sku' => '1111',
                    'name' => 'Testing2',
                ],
            ],
            'allowPartial' => false,
            'subscribe' => false,
        ];
        $payment = new Payment($data);
        $this->assertEquals(1, sizeof($payment->fields()));
        $this->assertEquals($data, $payment->toArray());
    }
}