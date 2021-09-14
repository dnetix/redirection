<?php

namespace Tests;

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
}
