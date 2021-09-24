<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Account;
use Dnetix\Redirection\Entities\Status;
use Tests\BaseTestCase;

class ServiceQueryTest extends BaseTestCase
{
    public function testItHandlesAPartialPaymentResponse()
    {
        $response = $this->getService()->query(10008);

        $this->assertEquals(10008, $response->requestId());
        $this->assertEquals(3, count($response->payment()));
        $this->assertNotNull($response->request());
    }

    public function testItHandlesANonExistentSession()
    {
        $response = $this->getService()->query(99999);

        $this->assertEmpty($response->requestId());
        $this->assertEquals(Status::ST_FAILED, $response->status()->status());
        $this->assertNull($response->request());
    }

    public function testItHandlesCustomNameValuePairs()
    {
        $response = $this->getService()->query(10010);

        $this->assertSame([
            'merchantCode' => '1065152',
            'terminalNumber' => '00990099',
            'credit' => [
                'code' => 1,
                'type' => '03',
                'groupCode' => 'X',
                'installments' => 9,
            ],
            'totalAmount' => 3809000,
            'interestAmount' => 0,
            'installmentAmount' => 423205.13,
            'iceAmount' => 0,
            'bin' => '365454',
            'expiration' => '1122',
            'lastDigits' => '0008',
        ], $response->lastTransaction()->additionalData());
    }

    public function testItHandlesABankAccountSubscription()
    {
        $response = $this->getService()->query(10009);

        $this->assertTrue($response->status()->isApproved());
        $this->assertEquals('account', $response->subscription()->type());

        $account = $response->subscription()->parseInstrument();
        $this->assertInstanceOf(Account::class, $account);

        $this->assertEquals('007', $account->bankCode());
        $this->assertEquals('Bancolombia', $account->bankName());
        $this->assertEquals('00849514000', $account->accountNumber());
        $this->assertEquals('4000', $account->lastDigits());
        $this->assertEquals('A', $account->accountType());
        $this->assertEquals('account', $account->type());

        $this->assertArrayHasKey('bankCode', $account->toArray());

        $this->assertNotEmpty($response->subscription()->instrumentToArray());

        $this->assertEquals('account', $response->subscription()->toArray()['type']);
    }
}
