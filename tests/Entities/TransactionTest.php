<?php

namespace Tests\Entities;

use Dnetix\Redirection\Entities\Status;
use Dnetix\Redirection\Entities\Transaction;
use Tests\BaseTestCase;

class TransactionTest extends BaseTestCase
{
    public function testItParsesTheDataCorrectly()
    {
        $data = json_decode('{"status": {"status": "APPROVED","reason": "00","message": "Aprobada","date": "2021-09-10T14:30:21-05:00"},"internalReference": 1518816265,"paymentMethod": "visa","paymentMethodName": "Visa","issuerName": "BANCO DE BOGOTA","amount": {"from": {"currency": "COP","total": 40000},"to": {"currency": "COP","total": 40000},"factor": 1},"authorization": "007102","reference": 649999,"receipt": "141344408","franchise": "CR_VS","refunded": false,"processorFields": [{"keyword": "merchantCode","value": "012044569","displayOn": "none"},{"keyword": "terminalNumber","value": "00031000","displayOn": "none"},{"keyword": "bin","value": "459918","displayOn": "none"},{"keyword": "expiration","value": "0323","displayOn": "none"},{"keyword": "installments","value": 1,"displayOn": "none"},{"keyword": "lastDigits","value": "4442","displayOn": "none"}]}', true);
        $transaction = new Transaction($data);

        $this->assertEquals(Status::ST_APPROVED, $transaction->status()->status());
        $this->assertTrue($transaction->status()->isApproved());
        $this->assertTrue($transaction->isSuccessful());
        $this->assertTrue($transaction->isApproved());
        $this->assertEquals('1518816265', $transaction->internalReference());
        $this->assertEquals('visa', $transaction->paymentMethod());
        $this->assertEquals('Visa', $transaction->paymentMethodName());
        $this->assertEquals('CR_VS', $transaction->franchise());
        $this->assertEquals('BANCO DE BOGOTA', $transaction->issuerName());
        $this->assertEquals('007102', $transaction->authorization());
        $this->assertEquals('649999', $transaction->reference());
        $this->assertEquals('141344408', $transaction->receipt());
        $this->assertFalse($transaction->refunded());

        $this->assertEquals(40000, $transaction->amount()->to()->total());
        $this->assertEquals('COP', $transaction->amount()->to()->currency());
        $this->assertEquals(40000, $transaction->amount()->from()->total());
        $this->assertEquals('COP', $transaction->amount()->from()->currency());
        $this->assertEquals(1, $transaction->amount()->factor());

        $this->assertEquals([
            'merchantCode' => '012044569',
            'terminalNumber' => '00031000',
            'bin' => '459918',
            'expiration' => '0323',
            'lastDigits' => '4442',
            'installments' => 1,
        ], $transaction->additionalData());

        $this->assertEquals([
            [
                'keyword' => 'merchantCode',
                'value' => '012044569',
                'displayOn' => 'none',
            ],
            [
                'keyword' => 'terminalNumber',
                'value' => '00031000',
                'displayOn' => 'none',
            ],
            [
                'keyword' => 'bin',
                'value' => '459918',
                'displayOn' => 'none',
            ],
            [
                'keyword' => 'expiration',
                'value' => '0323',
                'displayOn' => 'none',
            ],
            [
                'keyword' => 'installments',
                'value' => 1,
                'displayOn' => 'none',
            ],
            [
                'keyword' => 'lastDigits',
                'value' => '4442',
                'displayOn' => 'none',
            ],
        ], $transaction->processorFieldsToArray());

        $this->assertEquals($data, $transaction->toArray());
    }
}
