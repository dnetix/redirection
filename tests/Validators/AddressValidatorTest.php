<?php

namespace Tests\Validators;

use Dnetix\Redirection\Entities\Address;
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
            'phone' => '4442310',
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
        $this->assertEmpty($address->street());
    }
}
