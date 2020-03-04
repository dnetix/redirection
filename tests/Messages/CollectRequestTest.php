<?php

namespace Tests\Messages;

use Dnetix\Redirection\Message\CollectRequest;
use Tests\BaseTestCase;

class CollectRequestTest extends BaseTestCase
{
    public function testItParsesCorrectlyACollectWithCredit()
    {
        $data = [
            'locale' => 'es_CO',
            'payer' => [
                'name' => 'Cassie',
                'surname' => 'Walsh',
                'email' => 'lance04@lockman.com',
                'document' => '1040035020',
                'documentType' => 'CC'
            ],
            'payment' => [
                'reference' => 'TEST_20200304_202408',
                'description' => 'Et dolorem voluptate voluptatem porro delectus.',
                'amount' => [
                    'currency' => 'COP',
                    'total' => 143000
                ],
                'allowPartial' => false,
                'subscribe' => false,
            ],
            'instrument' => [
                'token' => [
                    'token' => 'e317950201950c59e91b6a59b25d439888a504579715a09bc0862c76b64335d9'
                ],
                'credit' =>         [
                    'code' => 500,
                    'type' => '02',
                    'groupCode' => 'P',
                    'installment' => 3
                ]
            ]
        ];
        $request = new CollectRequest($data);

        $this->assertEquals($data['instrument']['credit']['code'], $request->instrument()->credit()->code());
        $this->assertEquals($data['instrument']['credit']['type'], $request->instrument()->credit()->type());
        $this->assertEquals($data['instrument']['credit']['groupCode'], $request->instrument()->credit()->groupCode());
        $this->assertEquals($data['instrument']['credit']['installment'], $request->instrument()->credit()->installment());

        $this->assertEquals($data['instrument'], $request->instrument()->toArray());
        $this->assertEquals($data, $request->toArray());
    }
}
