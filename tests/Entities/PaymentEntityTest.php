<?php

namespace Tests\Entities;

use Dnetix\Redirection\Entities\Item;
use Dnetix\Redirection\Entities\Payment;
use Dnetix\Redirection\Entities\PaymentModifier;
use Dnetix\Redirection\Message\RedirectRequest;
use Tests\BaseTestCase;

class PaymentEntityTest extends BaseTestCase
{
    public function testItAddsAField(): void
    {
        $payment = new Payment();
        $this->assertEmpty($payment->fields());
        $this->assertEmpty($payment->fieldsToKeyValue());

        $payment->addField(['keyword' => 'testing', 'value' => 'value']);
        $this->assertEquals(1, count($payment->fields()));

        $payment->addField(['keyword' => 'testing2', 'value' => 'value2']);
        $this->assertEquals(2, count($payment->fields()));

        $this->assertArrayHasKey('testing2', $payment->fieldsToKeyValue());

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
            'allowPartial' => true,
        ];
        $payment = new Payment($data);
        $this->assertEquals(1, count($payment->fields()));
        $this->assertEquals($data, $payment->toArray());

        $payment->addField(['keyword' => 'testing', 'value' => 'value']);
        $this->assertEquals(2, count($payment->fields()));
        $this->assertEquals([
            [
                'displayOn' => 'none',
                'keyword' => 'no_empty',
                'value' => 'no_empty_value',
            ],
            [
                'displayOn' => 'none',
                'keyword' => 'testing',
                'value' => 'value',
            ],
        ], $payment->fieldsToArray());

        $this->assertEquals(2, count($payment->items()));
    }

    public function testItParsesCorrectlyTheItems(): void
    {
        $payment = new Payment([
            'reference' => 'required',
            'amount' => [
                'total' => 10000,
            ],
            'fields' => [
                [
                    'keyword' => 'no_empty',
                    'value' => 'no_empty_value',
                ],
            ],
            'items' => [
                [
                    'sku' => '1234',
                    'name' => 'Testing1',
                ],
                new Item([
                    'sku' => '1111',
                    'name' => 'Testing2',
                ]),
            ],
        ]);

        if (count($payment->items()) == 2 && is_array($payment->items())) {
            foreach ($payment->items() as $item) {
                $this->assertInstanceOf(Item::class, $item);
            }
        } else {
            $this->fail('Items not an array');
        }
    }

    public function testItAcceptsNoItems(): void
    {
        $payment = new Payment([
            'reference' => 'required',
            'amount' => [
                'total' => 10000,
            ],
            'fields' => [
                [
                    'keyword' => 'no_empty',
                    'value' => 'no_empty_value',
                ],
            ],
            'subscribe' => false,
        ]);
        $this->assertEmpty($payment->items());
    }

    public function testItParsesTheDataCorrectly(): void
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
            'recurring' => [
                'periodicity' => 'M',
                'interval' => 1,
                'notificationUrl' => 'https://dnetix.co/ping/recurring',
                'dueDate' => date('Y-m-d', strtotime('+6 months')),
            ],
            'shipping' => [
                'name' => 'Diego',
                'surname' => 'Calle',
                'email' => 'dnetix@gmail.com',
            ],
            'discount' => [
                'code' => 1231,
                'type' => 'MERCHANT',
                'amount' => 100,
                'base' => '10',
                'percent' => 0,
            ],
            'subscribe' => true,
        ];
        $payment = new Payment($data);

        $this->assertEquals(1, count($payment->fields()));
        $this->assertEquals($data, $payment->toArray());
    }

    public function testItHandlesABadEntityOnConstruction(): void
    {
        $data = [
            'reference' => 'required',
            'amount' => [
                'total' => 10000,
                'currency' => 'COP',
            ],
            'shipping' => (object)[
                'name' => 'Diego',
                'surname' => 'Calle',
                'email' => 'dnetix@gmail.com',
            ],
            'allowPartial' => false,
            'subscribe' => false,
        ];
        $payment = new Payment($data);

        $this->assertEmpty($payment->shipping());
    }

    public function testItParsesCorrectlyADispersion(): void
    {
        $data = [
            'buyer' => [
                'name' => 'Diego',
                'email' => 'diego.calle@placetopay.com',
            ],
            'payment' => [
                'reference' => 'TEST_3',
                'description' => 'Testing Payment',
                'amount' => [
                    'currency' => 'COP',
                    'total' => 243590,
                ],
                'dispersion' => [
                    [
                        'agreement' => 29,
                        'agreementType' => 'AIRLINE',
                        'amount' => [
                            'taxes' => [
                                [
                                    'kind' => 'valueAddedTax',
                                    'amount' => 30590,
                                ],
                                [
                                    'kind' => 'airportTax',
                                    'amount' => 16300,
                                ],
                            ],
                            'currency' => 'COP',
                            'total' => 207890,
                        ],
                    ],
                    [
                        'agreement' => null,
                        'agreementType' => 'MERCHANT',
                        'amount' => [
                            'taxes' => [
                                [
                                    'kind' => 'valueAddedTax',
                                    'amount' => 5700,
                                ],
                            ],
                            'currency' => 'COP',
                            'total' => 35700,
                        ],
                    ],
                ],
            ],
            'expiration' => date('c', strtotime('+1 day')),
            'returnUrl' => 'https://dnetix.co/ping/rtest',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Testing',
        ];
        $request = new RedirectRequest($data);

        $conversion = [
            'buyer' => [
                'name' => 'Diego',
                'email' => 'diego.calle@placetopay.com',
            ],
            'payment' => [
                'reference' => 'TEST_3',
                'description' => 'Testing Payment',
                'amount' => [
                    'currency' => 'COP',
                    'total' => 243590,
                ],
                'dispersion' => [
                    [
                        'reference' => 'TEST_3',
                        'description' => 'Testing Payment',
                        'agreement' => 29,
                        'agreementType' => 'AIRLINE',
                        'amount' => [
                            'taxes' => [
                                [
                                    'kind' => 'valueAddedTax',
                                    'amount' => 30590,
                                ],
                                [
                                    'kind' => 'airportTax',
                                    'amount' => 16300,
                                ],
                            ],
                            'currency' => 'COP',
                            'total' => 207890,
                        ],
                    ],
                    [
                        'reference' => 'TEST_3',
                        'description' => 'Testing Payment',
                        'agreementType' => 'MERCHANT',
                        'amount' => [
                            'taxes' => [
                                [
                                    'kind' => 'valueAddedTax',
                                    'amount' => 5700,
                                ],
                            ],
                            'currency' => 'COP',
                            'total' => 35700,
                        ],
                    ],
                ],
            ],
            'expiration' => date('c', strtotime('+1 day')),
            'returnUrl' => 'https://dnetix.co/ping/rtest',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Testing',
            'locale' => 'es_CO',
            'captureAddress' => false,
            'skipResult' => false,
            'noBuyerFill' => false,
        ];

        $this->assertEquals($conversion, $request->toArray());
    }

    public function testItCanGetAModifierByType(): void
    {
        $modifierData = [
            'type' => PaymentModifier::TYPE_FEDERAL_GOVERNMENT,
            'code' => '12983',
            'additional' => [
                'invoice' => '123456',
            ],
        ];

        $payment = new Payment([
            'modifiers' => [$modifierData],
        ]);

        $this->assertInstanceOf(PaymentModifier::class, $payment->modifier(PaymentModifier::TYPE_FEDERAL_GOVERNMENT));
        $this->assertEquals($modifierData, $payment->modifier(PaymentModifier::TYPE_FEDERAL_GOVERNMENT)->toArray());

        $this->assertNull($payment->modifier('unknown_class'));
    }

    public function testItCanGetAModifierByTypeAndCode(): void
    {
        $modifierData = [
            'type' => PaymentModifier::TYPE_FEDERAL_GOVERNMENT,
            'code' => '12983',
            'additional' => [
                'invoice' => '123456',
            ],
        ];

        $payment = new Payment([
            'modifiers' => [
                [
                    'type' => PaymentModifier::TYPE_FEDERAL_GOVERNMENT,
                    'code' => '122233',
                ],
                $modifierData,
            ],
        ]);

        $this->assertInstanceOf(PaymentModifier::class, $payment->modifier(PaymentModifier::TYPE_FEDERAL_GOVERNMENT, '12983'));
        $this->assertEquals($modifierData, $payment->modifier(PaymentModifier::TYPE_FEDERAL_GOVERNMENT, '12983')->toArray());

        $this->assertNull($payment->modifier(PaymentModifier::TYPE_FEDERAL_GOVERNMENT, 'unknown_code'));
    }

    public function testItParsesDiscountWithoutPercent(): void
    {
        $data = [
            'reference' => '23232',
            'amount' => [
                'total' => 10000,
                'currency' => 'COP',
            ],
            'discount' => [
                'code' => 18910,
                'type' => PaymentModifier::TYPE_FEDERAL_GOVERNMENT,
                'amount' => 500.0,
                'base' => 10.0,
            ],
        ];
        $payment = new Payment($data);

        $this->assertArrayNotHasKey('percent', $payment->discount()->toArray());
        $this->assertEquals($data, $payment->toArray());
    }
}
