<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Carrier\Authentication;
use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Tests\BaseTestCase;

class AuthenticationTest extends BaseTestCase
{
    public function testItHandlesNoLoginProvided()
    {
        $this->expectException(PlacetoPayException::class);
        new Authentication([]);
    }

    public function testItGeneratesNewAuthentications()
    {
        $auth = new Authentication([
            'login' => 'login_here',
            'tranKey' => 'ABCD1234',
        ]);

        $generated = $auth->asArray();
        $this->assertEquals('login_here', $generated['login']);
        $this->assertNotEquals('ABCD1234', $generated['tranKey']);
        $this->assertNotEmpty($generated['seed']);
        $this->assertNotEmpty($generated['nonce']);
    }

    public function testItOverridesTheAuthenticationSeedAndNonce()
    {
        $auth = new Authentication([
            'login' => 'login',
            'tranKey' => 'ABCD1234',
            'auth' => [
                'seed' => '2016-10-26T21:37:00+00:00',
                'nonce' => 'ifYEPnAcJbpDVR1t',
            ],
        ]);

        $data = $auth->asArray();

        $this->assertEquals('login', $data['login'], 'Login matches');
        $this->assertEquals('2016-10-26T21:37:00+00:00', $data['seed'], 'Seed matches');
        $this->assertEquals('aWZZRVBuQWNKYnBEVlIxdA==', $data['nonce'], 'Nonce matches');
        $this->assertEquals('Xi5xrRwrqPU21WE2JI4hyMaCvQ8=', $data['tranKey'], 'Trankey matches');

        $this->assertNotEmpty($auth->digest(false));
        $this->assertInstanceOf(\SoapHeader::class, $auth->asSoapHeader());
    }

    public function testItHandlesCorrectlyABadRequest()
    {
        $response = $this->getService([
            'login' => 'failed_login',
        ])->request($this->baseRequest());

        $this->assertEquals(Status::ST_FAILED, $response->status()->status());
        $this->assertFalse($response->isSuccessful());
    }

    public function testItHandlesAuthenticationAdditional()
    {
        $response = $this->getService([
            'authAdditional' => [
                'testing-auth' => 'ERROR-200',
            ],
        ])->request($this->baseRequest());

        $this->assertEquals(Status::ST_FAILED, $response->status()->status());
        $this->assertEquals(401, $response->status()->reason());
        $this->assertEquals('Autenticación fallida 200', $response->status()->message());
    }
}
