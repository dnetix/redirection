<?php

namespace Tests\Entities;

use Dnetix\Redirection\Entities\Item;
use Dnetix\Redirection\Entities\Payment;
use Dnetix\Redirection\Message\RedirectRequest;
use Tests\BaseTestCase;

class PaymentEntityTest extends BaseTestCase
{
    public function testItAddsAField()
    {
        $payment = new Payment();
        $this->assertNull($payment->fields());

        $payment->addField(['keyword' => 'testing', 'value' => 'value']);
        $this->assertEquals(1, sizeof($payment->fields()));

        $payment->addField(['keyword' => 'testing2', 'value' => 'value2']);
        $this->assertEquals(2, sizeof($payment->fields()));

        $data = [
            'reference' => 'required',
            'amount' => [
                'total' => 10000,
                'currency' => 'COP'
            ],
            'fields' => [
                [
                    'keyword' => 'no_empty',
                    'value' => 'no_empty_value',
                    'displayOn' => 'none'
                ]
            ],
            'items' => [
                [
                    'sku' => '1234',
                    'name' => 'Testing1'
                ],
                [
                    'sku' => '1111',
                    'name' => 'Testing2'
                ]
            ],
            'allowPartial' => false,
            'subscribe' => false,
        ];
        $payment = new Payment($data);
        $this->assertEquals(1, sizeof($payment->fields()));
        $this->assertEquals($data, $payment->toArray());

        $payment->addField(['keyword' => 'testing', 'value' => 'value']);
        $this->assertEquals(2, sizeof($payment->fields()));
        $this->assertEquals([
            [
                'displayOn' => 'none',
                'keyword' => 'no_empty',
                'value' => 'no_empty_value'
            ],
            [
                'displayOn' => 'none',
                'keyword' => 'testing',
                'value' => 'value'
            ]
        ], $payment->fieldsToArray());

        $this->assertEquals(2, sizeof($payment->items()));
    }

    public function testItParsesCorrectlyTheItems()
    {
        $payment = new Payment([
            'reference' => 'required',
            'amount' => [
                'total' => 10000
            ],
            'fields' => [
                [
                    'keyword' => 'no_empty',
                    'value' => 'no_empty_value'
                ]
            ],
            'items' => [
                [
                    'sku' => '1234',
                    'name' => 'Testing1'
                ],
                new Item([
                    'sku' => '1111',
                    'name' => 'Testing2'
                ])
            ]
        ]);

        if (sizeof($payment->items()) == 2 && is_array($payment->items())) {
            foreach ($payment->items() as $item) {
                $this->assertInstanceOf(Item::class, $item);
            }
        } else {
            $this->fail('Items not an array');
        }
    }

    public function testItAcceptsNoItems()
    {
        $payment = new Payment([
            'reference' => 'required',
            'amount' => [
                'total' => 10000
            ],
            'fields' => [
                [
                    'keyword' => 'no_empty',
                    'value' => 'no_empty_value'
                ]
            ],
            'subscribe' => false,
        ]);
        $this->assertNull($payment->items());
    }

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

    public function testItParsesCorrectlyADispersion()
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
                        'allowPartial' => false,
                        'subscribe' => false,
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
                        'allowPartial' => false,
                        'subscribe' => false,
                    ],
                ],
                'allowPartial' => false,
                'subscribe' => false,
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
}
