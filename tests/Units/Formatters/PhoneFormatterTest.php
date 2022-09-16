<?php

namespace Tests\Units\Formatters;

use Carbon\Carbon;
use Devesharp\Exceptions\Exception;
use Devesharp\Generators\ControllerGenerator;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Devesharp\Support\Formatters\PhoneFormatter;
use Devesharp\Support\Formatters\PriceFormatter;
use Illuminate\Routing\Controller;
use Tests\Units\Presenter\Mocks\ModelPresenter;
use Tests\Units\Transformer\Mocks\ModelStub;
use Tests\Units\Transformer\Mocks\ModelStub2;
use Tests\Units\Transformer\Mocks\TransformerStub;

class PhoneFormatterTest extends \Tests\TestCase
{
    /**
     * @testdox Testando formatação de preço
     */
    public function testPhoneFormatter()
    {
        $this->assertEquals('', format(PhoneFormatter::class, null));
        $this->assertEquals('', format(PhoneFormatter::class, ''));
        $this->assertEquals('(11) 98999-9999', format(PhoneFormatter::class, '11989999999'));
        $this->assertEquals('(11) 8999-9999', format(PhoneFormatter::class, '1189999999'));
    }
}
