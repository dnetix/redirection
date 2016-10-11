<?php


class ReverseTest extends TestCase
{

    public function testItSOAPReverseATransaction()
    {
        // Construct the gateway providing the configuration values
        $gateway = $this->getGateway();
        $response = $gateway->reverse('1442625531');
        var_dump($response);
        // Verifies the process url
        if ($response->isSuccessful()){
        } else{
            $this->fail($response->status()->message());
        }
    }

}