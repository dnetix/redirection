<?php

namespace Tests;

use Dnetix\Redirection\Contracts\Gateway;
use Dnetix\Redirection\PlacetoPay;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function getGateway($overrides = []): PlacetoPay
    {
        return new PlacetoPay(array_merge([
            'login' => 'not_the_real_login_obviously',
            'tranKey' => 'kXf6FDYdQTH4dhwWs3Ue',
            'url' => 'https://checkout-test.placetopay.com',
        ], $overrides));
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
