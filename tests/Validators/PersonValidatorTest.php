<?php

namespace Tests\Validators;

use Dnetix\Redirection\Entities\Person;
use Tests\BaseTestCase;

class PersonValidatorTest extends BaseTestCase
{
    public function testItPassesWhenAllOk()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe@example.com',
            'document' => '1040035000',
            'documentType' => 'CC',
            'company' => 'Acme S.A.S.',
            'address' => [
                'street' => '707 Evergreen',
                'city' => 'Medellín',
                'country' => 'CO',
            ],
        ];
        $person = new Person($data);
        $this->assertEquals($data['name'], $person->name());
        $this->assertEquals($data['surname'], $person->surname());
        $this->assertEquals($data['email'], $person->email());
        $this->assertEquals($data['document'], $person->document());
        $this->assertEquals($data['documentType'], $person->documentType());
        $this->assertEquals($data['company'], $person->company());

        $this->assertEquals($data, $person->toArray());
    }

    public function testItAllowsEmptyInstantiation()
    {
        $person = new Person();
        $this->assertEmpty($person->name());
    }

    public function testItPassesAPortugueseName()
    {
        $data = [
            'name' => 'ASSUNÇÃO',
            'surname' => 'DoÑe',
            'email' => 'johndoe@example.com',
            'document' => '1040035000',
            'documentType' => 'CC',
            'company' => 'Acme S.A.S.',
        ];
        $person = new Person($data);
        $this->assertEquals($data['name'], $person->name());
    }
}
