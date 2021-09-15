<?php

namespace Tests\Entities;

use Dnetix\Redirection\Carrier\SoapCarrier;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Dnetix\Redirection\Helpers\Settings;
use GuzzleHttp\Client;
use Tests\BaseTestCase;

class SettingsTest extends BaseTestCase
{
    public function testItFailsIfNoLoginProvided()
    {
        $this->expectException(PlacetoPayException::class);
        $this->getSettings([
            'login' => null,
        ]);
    }

    public function testItFailsIfNoBaseURLProvided()
    {
        $this->expectException(PlacetoPayException::class);
        $this->getSettings([
            'baseUrl' => null,
        ]);
    }

    public function testItHandlesABadTypeProvided()
    {
        $settings = $this->getSettings([
            'type' => 'not_exists',
        ]);
        $this->assertEquals(Settings::TP_REST, $settings->type());
    }

    public function testItWorksOkWithAccessors()
    {
        $location = 'https://dnetix.co/some_wsdl';
        $settings = $this->getSettings([
            'client' => null,
            'type' => Settings::TP_SOAP,
            'baseUrl' => 'https://redirection.test',
            'wsdl' => $location . '?wsdl',
            'location' => $location,
        ]);
        $this->assertEquals(Settings::TP_SOAP, $settings->type());
        $this->assertEquals('https://redirection.test/soap/redirect?wsdl', $settings->wsdl());
        $this->assertEquals('https://redirection.test/soap/redirect', $settings->location());
        $this->assertEmpty($settings->toArray());

        $carrier = $settings->carrier();
        $this->assertInstanceOf(SoapCarrier::class, $carrier);
        // It uses the same one
        $this->assertSame($carrier, $settings->carrier());

        $this->assertInstanceOf(Client::class, $settings->client());
    }
}
