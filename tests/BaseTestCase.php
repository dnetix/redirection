<?php

namespace Tests;

use Dnetix\Redirection\Helpers\Settings;
use Dnetix\Redirection\PlacetoPay;
use PHPUnit\Framework\TestCase;

class BaseTestCase extends TestCase
{
    public function getDefaultSettings(array $overrides = []): array
    {
        return array_merge([
            'login' => 'not_the_real_login_obviously',
            'tranKey' => 'kXf6FDYdQTH4dhwWs3Ue',
            'url' => 'https://checkout-test.placetopay.com',
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
}
