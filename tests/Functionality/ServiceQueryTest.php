<?php

namespace Tests\Functionality;

use Dnetix\Redirection\Entities\Account;
use Tests\BaseTestCase;

class ServiceQueryTest extends BaseTestCase
{
    public function testItHandlesAPartialPaymentResponse()
    {
        $response = $this->getService()->query(10008);

        $this->assertEquals(10008, $response->requestId());
        $this->assertEquals(3, count($response->payment()));
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
