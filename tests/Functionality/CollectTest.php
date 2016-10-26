<?php


class CollectTest extends TestCase
{
    protected $data = [
        'payer' => [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe@example.com',
            'document' => '1040035000',
            'documentType' => 'CC',
        ],
        'payment' => [
            'reference' => 'TESTING123456',
            'amount' => [
                'currency' => 'COP',
                'total' => '10000',
            ],
        ],
        'instrument' => [
            'token' => [
                'token' => '961da9f371a8edc212a525f5e8d69934bec8484f546c720d3c5bf75350602ba0',
            ],
        ],
    ];

    public function testItSOAPCollectsATokenPayment()
    {
        $gateway = $this->getGateway();
        $response = $gateway->collect($this->data);
        // Verifies the process url
        if ($response->isSuccessful()) {
        } else {
            $this->fail($response->status()->message());
        }
    }

}