<?php

namespace Tests\Entities;

use Dnetix\Redirection\Exceptions\PlacetoPayException;
use Tests\BaseTestCase;

class SettingsTest extends BaseTestCase
{
    public function testItFailsIfNoLoginProvided()
    {
        $this->expectException(PlacetoPayException::class);
        $this->getSettings([
            'login' => null,
        ]);
    }

    public function testItFailsIfNoBaseURLProvided()
    {
        $this->expectException(PlacetoPayException::class);
        $this->getSettings([
            'baseUrl' => null,
        ]);
    }
}
