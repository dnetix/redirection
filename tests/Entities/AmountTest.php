<?php

namespace Tests\Entities;

use Dnetix\Redirection\Entities\Amount;
use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Tests\BaseTestCase;

class AmountTest extends BaseTestCase
{
    public function testItWorksWithTaxes()
    {
        $amount = new Amount([
            'taxes' => $taxes = [
                [
                    'kind' => 'valueAddedTax',
                    'amount' => 100,
                    'base' => 10,
                ],
                [
                    'kind' => 'exciseDuty',
                    'amount' => 200,
                    'base' => 20,
                ],
                [
                    'kind' => 'ice',
                    'amount' => 300,
                    'base' => 30,
                ],
                [
                    'kind' => 'airportTax',
                    'amount' => 400,
                    'base' => 40,
                ],
                [
                    'kind' => 'stateTax',
                    'amount' => 500,
                    'base' => 50,
                ],
                [
                    'kind' => 'municipalTax',
                    'amount' => 600,
                    'base' => 60,
                ],
                [
                    'kind' => 'reducedStateTax',
                    'amount' => 700,
                    'base' => 70,
                ],
            ],
        ]);

        $this->assertCount(7, $amount->taxes());

        foreach ($taxes as $i => $tax) {
            $this->assertInstanceOf('Dnetix\Redirection\Entities\TaxDetail', $amount->taxes()[$i]);
            $this->assertSame($tax['kind'], $amount->taxes()[$i]->kind());
            $this->assertSame((float)$tax['amount'], $amount->taxes()[$i]->amount());
            $this->assertSame((float)$tax['base'], $amount->taxes()[$i]->base());
        }
    }

    public function testItWorksWithDetails()
    {
        $amount = new Amount([
            'details' => $details = [
                [
                    'kind' => 'discount',
                    'amount' => 10,
                ],
                [
                    'kind' => 'additional',
                    'amount' => 20,
                ],
                [
                    'kind' => 'vatDevolutionBase',
                    'amount' => 30,
                ],
                [
                    'kind' => 'shipping',
                    'amount' => 40,
                ],
                [
                    'kind' => 'handlingFee',
                    'amount' => 50,
                ],
                [
                    'kind' => 'insurance',
                    'amount' => 60,
                ],
                [
                    'kind' => 'giftWrap',
                    'amount' => 70,
                ],
                [
                    'kind' => 'subtotal',
                    'amount' => 80,
                ],
                [
                    'kind' => 'fee',
                    'amount' => 90,
                ],
                [
                    'kind' => 'tip',
                    'amount' => 100,
                ],
                [
                    'kind' => 'airline',
                    'amount' => 110,
                ],
                [
                    'kind' => 'interest',
                    'amount' => 120,
                ],
            ],
        ]);

        $this->assertCount(12, $amount->details());

        foreach ($details as $i => $detail) {
            $this->assertInstanceOf('Dnetix\Redirection\Entities\AmountDetail', $amount->details()[$i]);
            $this->assertSame($detail['kind'], $amount->details()[$i]->kind());
            $this->assertSame((float)$detail['amount'], $amount->details()[$i]->amount());
        }
    }
}
