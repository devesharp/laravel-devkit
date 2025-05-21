<?php

namespace Tests\Support;

use Devesharp\Support\Collection;

class MasksTest extends \Tests\TestCase
{
    /**
     * @testdox OnlyNumbers
     */
    public function testOnlyNumbers()
    {
        $this->assertEquals('11', \Devesharp\Support\Masks::onlyNumbers('sdnj11d'));
    }

    /**
     * @testdox CEP
     */
    public function testCEP()
    {
        $this->assertEquals('08828-102', \Devesharp\Support\Masks::CEP('08828102'));
        $this->assertEquals('08828-10', \Devesharp\Support\Masks::CEP('08828-10-/'));
    }

    /**
     * @testdox CNPJ
     */
    public function testCNPJ()
    {
        $this->assertEquals('01.390.036/0001-91', \Devesharp\Support\Masks::CNPJ('01.390.036/0001-91'));
        $this->assertEquals('01.390.036/0001-91', \Devesharp\Support\Masks::CNPJ('01390036000191'));
    }

    /**
     * @testdox CPF
     */
    public function testCPF()
    {
        $this->assertEquals('632.745.580-87', \Devesharp\Support\Masks::CPF('63274558087'));
        $this->assertEquals('886.000.110-27', \Devesharp\Support\Masks::CPF('886.000.110-27'));
    }

    /**
     * @testdox CNPJAndCPF
     */
    public function testCNPJAndCPF()
    {
        $this->assertEquals('632.745.580-87', \Devesharp\Support\Masks::CNPJAndCPF('63274558087'));
        $this->assertEquals('01.390.036/0001-91', \Devesharp\Support\Masks::CNPJAndCPF('01390036000191'));
        $this->assertEquals('886.000.110-27', \Devesharp\Support\Masks::CNPJAndCPF('886.000.110-27'));
    }

    /**
     * @testdox RG
     */
    public function testRG()
    {
        $this->assertEquals('23.231.233-2X', \Devesharp\Support\Masks::RG('23-231-233-2X'));
        $this->assertEquals('23.231.233-22', \Devesharp\Support\Masks::RG('23-231-233-22'));
    }

    /**
     * @testdox PhoneMask
     */
    public function testPhoneMask()
    {
        $this->assertEquals('(11) 99999-8888', \Devesharp\Support\Masks::PhoneMask('11 999998888'));
        $this->assertEquals('(11) 9999-8888', \Devesharp\Support\Masks::PhoneMask('11 99998888'));
        $this->assertEquals('(11) 99999-8888', \Devesharp\Support\Masks::PhoneMask('11 999998888'));
    }

    /**
     * @testdox DateMask
     */
    public function testDateMask()
    {
        $this->assertEquals('11/02/2021', \Devesharp\Support\Masks::DateMask('11022021'));
        $this->assertEquals('11/10/2021', \Devesharp\Support\Masks::DateMask('11/10/2021'));
    }

    /**
     * @testdox NoSpaces
     */
    public function testNoSpaces()
    {
        $this->assertEquals('anystringã', \Devesharp\Support\Masks::NoSpaces('any string  ã'));
    }
}
