<?php

namespace Tests\Validators;

use Dnetix\Redirection\Entities\Person;
use Dnetix\Redirection\Exceptions\EntityValidationFailException;
use Dnetix\Redirection\Validators\PersonValidator;
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
                'country' => 'CO'
            ]
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

    public function testItPassesWhenStreetOk()
    {
        $this->assertEquals(1, preg_match(PersonValidator::getPattern('STREET'), 'Calle 5ta No 24 - 34, Unidad Bolivariana (Torre 24 apto 202)'));
    }

    public function testItFailWhenStreetItsInvalid()
    {
        $this->assertEquals(0, preg_match(PersonValidator::getPattern('STREET'), '<> Calle 5ta No 24 - 34, Unidad Bolivariana (Torre 24 apto 202)'));
    }

    public function testItAllowsEmptyInstantiation()
    {
        $person = new Person();
        $this->assertNull($person->name());
    }

    public function testItPassesAPortugueseName()
    {
        $data = [
            'name' => 'ASSUNÇÃO',
            'surname' => 'DoÑe',
            'email' => 'johndoe@example.com',
            'document' => '1040035000',
            'documentType' => 'CC',
            'company' => 'Acme S.A.S.'
        ];
        try {
            $person = new Person($data);
            $person->isValid($fields, false);
            $this->assertEquals($data['name'], $person->name());
        } catch (EntityValidationFailException $e) {
            $this->fail('There should be no exception here');
        }
    }

    public function testItFailsIfSurnameItsInvalid()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe@example.com',
            'document' => '104003500000000000000000000',
            'documentType' => 'CC',
            'company' => 'Acme S.A.S.'
        ];
        try {
            $person = (new Person($data))->isValid($fields, false);
            $this->fail('Entity person should throw an exception when not silent');
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['documentType', 'document'], $e->fields());
            $this->assertEquals('Person', $e->from());
        }
    }

    public function testItFailsIfDocumentTypeItsInvalid()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe@example.com',
            'document' => '1040035000',
            'documentType' => 'Cedula',
            'company' => 'Acme S.A.S.'
        ];
        try {
            $person = (new Person($data))->isValid($fields, false);
            $this->fail('Entity person should not be validated');
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['documentType', 'document'], $e->fields());
            $this->assertEquals('Person', $e->from());
        }
    }

    public function testItFailsIfDocumentItsInvalid()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'johndoe@example.com',
            'document' => 'INVALID',
            'documentType' => 'CC',
            'company' => 'Acme S.A.S.'
        ];
        try {
            $person = (new Person($data))->isValid($fields, false);
            $this->fail('Entity person should not be validated');
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['documentType', 'document'], $e->fields());
            $this->assertEquals('Person', $e->from());
        }
    }

    public function testItFailsIfEmailItsInvalid()
    {
        $data = [
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'INVALID',
            'document' => '1040035000',
            'documentType' => 'CC',
            'company' => 'Acme S.A.S.'
        ];
        try {
            $person = (new Person($data))->isValid($fields, false);
            $this->fail('Entity person should not be validated');
        } catch (EntityValidationFailException $e) {
            $this->assertEquals(['email'], $e->fields());
            $this->assertEquals('Person', $e->from());
        }
    }
}
