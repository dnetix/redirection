<?php


class ReverseTest extends TestCase
{

    public function testItSOAPQueryRequestInformation()
    {
        // Construct the gateway providing the configuration values
        $gateway = $this->getGateway();
        $response = $gateway->reverse('1442625531');
        // Verifies the process url
        if ($response->isSuccessful()){
            var_dump($response);
        } else{
            $this->fail($response->status()->message());
        }
    }

}