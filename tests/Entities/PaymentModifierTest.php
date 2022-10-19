<?php

namespace Tests\Entities;

use Dnetix\Redirection\Entities\PaymentModifier;
use Tests\BaseTestCase;

class PaymentModifierTest extends BaseTestCase
{
    private array $data;

    public function testItCanCreateAPaymentModifier(): void
    {
        $modifier = new PaymentModifier($this->data);

        $this->assertInstanceOf(PaymentModifier::class, $modifier);
        $this->assertEquals($this->data, $modifier->toArray());
        $this->assertEquals($this->data['type'], $modifier->type());
        $this->assertEquals($this->data['code'], $modifier->code());
        $this->assertEquals($this->data['additional'], $modifier->additional());
        $this->assertEquals($this->data['additional']['invoice'], $modifier->additional('invoice'));
    }

    public function testItCanSetDataIntoAPaymentModifier(): void
    {
        $modifier = new PaymentModifier();

        $modifier->setType($this->data['type']);
        $modifier->setCode($this->data['code']);
        $modifier->setAdditional($this->data['additional']);

        $this->assertInstanceOf(PaymentModifier::class, $modifier);
        $this->assertEquals($this->data, $modifier->toArray());
        $this->assertEquals($this->data['type'], $modifier->type());
        $this->assertEquals($this->data['code'], $modifier->code());
        $this->assertEquals($this->data['additional'], $modifier->additional());
        $this->assertEquals($this->data['additional']['invoice'], $modifier->additional('invoice'));
    }

    public function testItCanMergeAPaymentModifierAdditional(): void
    {
        $modifier = new PaymentModifier($this->data);

        $modifier->mergeAdditional([
            'law' => '18999',
        ]);

        $this->assertEquals($this->data['additional']['invoice'], $modifier->additional('invoice'));
        $this->assertEquals('18999', $modifier->additional('law'));
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'type' => PaymentModifier::TYPE_FEDERAL_GOVERNMENT,
            'code' => '12983',
            'additional' => [
                'invoice' => '123456',
            ],
        ];
    }
}
