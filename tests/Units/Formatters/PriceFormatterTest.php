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

class PriceFormatterTest extends \Tests\TestCase
{
    /**
     * @testdox Testando formatação de preço
     */
    public function testPriceWithDecimals()
    {
        $this->assertEquals('0,00', format(PriceFormatter::class, null));
        $this->assertEquals('0,00', format(PriceFormatter::class, ''));
        $this->assertEquals('1.000,00', format(PriceFormatter::class, 100000, true));
        $this->assertEquals('1.000', format(PriceFormatter::class, 100000, false));
    }
}
