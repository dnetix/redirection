<?php


class QueryTest extends TestCase
{

    public function testItSOAPQueryRequestInformation()
    {
        // Construct the gateway providing the configuration values
        $gateway = $this->getGateway();
        $response = $gateway->query('60');
        // Verifies the process url
        if ($response->isSuccessful()) {
        } else {
            $this->fail($response->status()->message());
        }
    }

}