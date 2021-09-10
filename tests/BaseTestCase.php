<?php

namespace Tests;

use Dnetix\Redirection\Contracts\Gateway;
use Dnetix\Redirection\PlacetoPay;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function getGateway($data = []): Gateway
    {
        return new PlacetoPay(array_merge([
            'login' => getenv('P2P_LOGIN'),
            'tranKey' => getenv('P2P_TRANKEY'),
            'url' => getenv('P2P_URL'),
        ], $data));
    }

    /**
     * Adds required data to the test request, given that PHPUnit does not have a userAgent or ipAddress.
     */
    public function addRequest(array $request): array
    {
        return array_merge([
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'PHPUnit',
        ], $request);
    }
}
