<?php

namespace Tests\Messages;

use Dnetix\Redirection\Message\RedirectRequest;
use Tests\BaseTestCase;

class RedirectRequestTest extends BaseTestCase
{
    public function testItParsesCorrectlyAPaymentRequest()
    {
        $data = [
            'locale' => 'en_US',
            'payer' => [
                'name' => 'Diego',
                'surname' => 'Calle',
                'email' => 'diego@testing.com',
                'documentType' => 'CC',
                'document' => '123456789',
                'mobile' => '3006108300',
                'address' => [
                    'street' => 'Fake street 123',
                    'city' => 'Medellin',
                    'state' => 'Antioquia',
                    'postalCode' => '050012',
                    'country' => 'CO',
                    'phone' => '4442310',
                ],
            ],
            'buyer' => [
                'name' => 'Johan',
                'surname' => 'Arango',
                'email' => 'joahn@testing.com',
                'documentType' => 'CC',
                'document' => '987654321',
                'mobile' => '3006108301',
                'address' => [
                    'street' => 'Fake street 321',
                    'city' => 'Bogota',
                    'state' => 'Bogota',
                    'postalCode' => '010012',
                    'country' => 'CO',
                    'phone' => '4442311',
                ],
            ],
            'payment' => [
                'reference' => 'Testing_2017',
                'description' => 'Testing payment for PHPUnit',
                'amount' => [
                    'taxes' => [
                        [
                            'kind' => 'valueAddedTax',
                            'amount' => 1.2,
                            'base' => 8,
                        ],
                    ],
                    'details' => [
                        [
                            'kind' => 'tip',
                            'amount' => 1,
                        ],
                        [
                            'kind' => 'insurance',
                            'amount' => 0.1,
                        ],
                    ],
                    'currency' => 'USD',
                    'total' => 10.283,
                ],
                'recurring' => [
                    'periodicity' => 'D',
                    'interval' => 7,
                    'nextPayment' => '2017-06-01',
                    'maxPeriods' => 4,
                    'notificationUrl' => 'http://recurring-notification.com/hello',
                ],
                'shipping' => [
                    'name' => 'Freddy',
                    'surname' => 'Mendivelso',
                    'email' => 'freddy@testing.com',
                    'documentType' => 'CC',
                    'document' => '918273645',
                    'mobile' => '3006108302',
                    'address' => [
                        'street' => 'Fake street 213',
                        'city' => 'Medellin',
                        'state' => 'Antioquia',
                        'postalCode' => '050012',
                        'country' => 'CO',
                        'phone' => '4442312',
                    ],
                ],
                'allowPartial' => true,
                'subscribe' => true,
            ],
            'expiration' => '2018-05-18T21:42:21+00:00',
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'PHPUnit',
            'returnUrl' => 'http://your-return-url.com',
            'cancelUrl' => 'http://your-cancel-url.com',
            'skipResult' => true,
            'noBuyerFill' => true,
            'captureAddress' => true,
            'paymentMethod' => 'CR_VS,_ATH_',
        ];
        $request = new RedirectRequest($data);

        $this->assertEquals($data['locale'], $request->locale());
        $this->assertEquals('EN', $request->language());
        $this->assertEquals($data['payment']['reference'], $request->reference());
        $this->assertTrue($request->payment()->allowPartial());
        $this->assertEquals($data['returnUrl'], $request->returnUrl());
        $this->assertEquals($data['cancelUrl'], $request->cancelUrl());

        $this->assertEquals($data, $request->toArray());
    }

    public function testItParsesCorrectlyASubscriptionRequest()
    {
        $data = [
            'buyer' => [
                'name' => 'Johan',
                'surname' => 'Arango',
                'email' => 'joahn@testing.com',
                'documentType' => 'CC',
                'document' => '987654321',
                'mobile' => '3006108301',
                'address' => [
                    'street' => 'Fake street 321',
                    'city' => 'Bogota',
                    'state' => 'Bogota',
                    'postalCode' => '010012',
                    'country' => 'CO',
                    'phone' => '4442311',
                ],
            ],
            'subscription' => [
                'reference' => 'Testing_S_2017',
                'description' => 'Testing payment for PHPUnit',
            ],
            'ipAddress' => '127.0.0.1',
            'userAgent' => 'PHPUnit',
        ];

        $additional = [
            'expiration' => '2018-05-18T21:42:21+00:00',
            'returnUrl' => 'http://your-return-url.com',
            'cancelUrl' => 'http://your-cancel-url.com',
            'skipResult' => true,
            'noBuyerFill' => true,
            'captureAddress' => true,
            'paymentMethod' => 'CR_VS,_ATH_',
            'userAgent' => 'PHPUnit',
            'ipAddress' => '127.0.0.12',
        ];

        $request = new RedirectRequest($data);
        $request->setReturnUrl($additional['returnUrl'])
            ->setIpAddress($additional['ipAddress'])
            ->setUserAgent($additional['userAgent'])
            ->setExpiration($additional['expiration'])
            ->setCancelUrl($additional['cancelUrl']);

        $this->assertEquals($data['subscription']['reference'], $request->reference());

        $this->assertEquals($additional['returnUrl'], $request->returnUrl());
        $this->assertEquals($additional['ipAddress'], $request->ipAddress());
        $this->assertEquals($additional['userAgent'], $request->userAgent());
        $this->assertEquals($additional['expiration'], $request->expiration());
        $this->assertEquals($additional['cancelUrl'], $request->cancelUrl());
    }

    public function testItHandlesADispersionRequest()
    {
        $data = json_decode('{"payment": {"amount": {"taxes": [{"base": 1885200,"kind": "valueAddedTax","amount": 47130},{"base": 0,"kind": "airportTax","amount": 603100}],"total": 3809000,"currency": "COP"},"reference": "800166551","subscribe": false,"dispersion": [{"amount": {"taxes": [{"base": 0,"kind": "valueAddedTax","amount": 47130},{"base": 0,"kind": "airportTax","amount": 603100}],"total": 2535430,"currency": "COP"},"agreement": 30,"reference": "800166551","subscribe": false,"description": "Pago en micrositio","allowPartial": false,"agreementType": "AIRLINE"},{"amount": {"taxes": [{"base": 0,"kind": "valueAddedTax","amount": 0}],"total": 1273570,"currency": "COP"},"reference": "800166551","subscribe": false,"description": "Pago en micrositio","agreement": null,"agreementType": "MERCHANT"}],"description": "Pago en micrositio","allowPartial": false},"ipAddress": "186.84.220.137","returnUrl": "https://sites.placetopay.com/colreservas/payments/c9b7f796dbc707a555a73a0aa14388be878ed69482513150e3ae6d6307e05d44/992939eb60","userAgent": "Mozilla/5.0 (iPhone; CPU iPhone OS 14_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Mobile/15E148 Safari/604.1","expiration": "2021-07-19T17:05:23-05:00"}', true);
        $request = new RedirectRequest($data);

        $this->assertSame(30, $request->payment()->dispersion()[0]->agreement());
        $this->assertNull($request->payment()->dispersion()[1]->agreement());
    }
}
