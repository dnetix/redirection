<?php


class RequestTest extends TestCase
{
    protected $data = [
        'payment' => [
            'reference' => 'TESTING123456',
            'amount' => [
                'currency' => 'COP',
                'total' => '10000'
            ]
        ],
        'returnUrl' => 'http://your.url.com/return?reference=TESTING123456'
    ];

    public function testItMakesASOAPPaymentRequest()
    {
        // Construct the gateway providing the configuration values
        $gateway = $this->getGateway();
        // Create the request data in this case its a variable
        $data = $this->addRequest($this->data);
        // Create a request to the system
        $response = $gateway->request($data);
        // Verifies the process url
        if ($response->isSuccessful()){
            var_dump($response->toArray());
        } else{
            $this->fail($response->status()->message());
        }
    }

}