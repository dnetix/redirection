<?php

namespace Tests;

use Dnetix\Redirection\Helpers\Settings;
use Dnetix\Redirection\PlacetoPay;
use PHPUnit\Framework\TestCase;
use Tests\Mocks\RestCarrierMock;

class BaseTestCase extends TestCase
{
    public function getDefaultSettings(array $overrides = []): array
    {
        return array_replace([
            'client' => RestCarrierMock::client(),
            'login' => 'not_the_real_login_obviously',
            'tranKey' => 'kXf6FDYdQTH4dhwWs3Ue',
            'baseUrl' => 'https://checkout-test.placetopay.com',
        ], $overrides);
    }

    public function getSettings(array $overrides = []): Settings
    {
        return new Settings($this->getDefaultSettings($overrides));
    }

    public function getService(array $overrides = []): PlacetoPay
    {
        return new PlacetoPay($this->getDefaultSettings($overrides));
    }

    public function baseRequest(array $overrides = []): array
    {
        return array_replace_recursive([
            'payment' => [
                'reference' => 'TEST_20210913_120000',
                'amount' => [
                    'total' => 12844,
                    'currency' => 'COP',
                ],
            ],
            'returnUrl' => 'https://dnetix.co/ping/example',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_2) AppleWebKit/601.3.9 (KHTML, like Gecko) Version/9.0.2 Safari/601.3.9',
        ], $overrides);
    }
}
