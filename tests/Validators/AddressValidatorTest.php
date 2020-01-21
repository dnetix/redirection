<?php

namespace Tests\Validators;

use Dnetix\Redirection\Entities\Address;
use Dnetix\Redirection\Exceptions\EntityValidationFailException;
use Tests\BaseTestCase;

class AddressValidatorTest extends BaseTestCase
{
    public function testItPassesWhenAllOk()
    {
        $data = [
            'street' => '707 Evergreen',
            'city' => 'Medellín',
            'country' => 'CO',
            'state' => 'Antioquia',
            'postalCode' => '050012',
            'phone' => '4442310'
        ];
        $address = new Address($data);
        $this->assertEquals($data['street'], $address->street());
        $this->assertEquals($data['city'], $address->city());
        $this->assertEquals($data['country'], $address->country());
        $this->assertEquals($data['state'], $address->state());
        $this->assertEquals($data['postalCode'], $address->postalCode());
        $this->assertEquals($data['phone'], $address->phone());

        $this->assertEquals($data, $address->toArray());
    }

    public function testItAllowsEmptyInstantiation()
    {
        $address = new Address();
        $this->assertNull($address->street());
    }

    public function testItFailsWhenNoRequiredProvided()
    {
        $data = [
            'state' => 'Antioquia',
            'postalCode' => '050012',
            'phone' => '+5744442310 ext 1503'
        ];
        try {
            $address = (new Address($data))->isValid($fields, false);
            $this->fail('Entity person should not be validated');
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['street', 'country'], $e->fields());
            $this->assertEquals('Address', $e->from());
        }
    }

    public function testItFailsWhenWrongCountryProvided()
    {
        $data = [
            'street' => '707 Evergreen',
            'city' => 'Medellín',
            'country' => 'Colombia',
        ];
        try {
            $address = (new Address($data))->isValid($fields, false);
            $this->fail('Entity person should not be validated');
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['country'], $e->fields());
            $this->assertEquals('Address', $e->from());
        }
    }
}
