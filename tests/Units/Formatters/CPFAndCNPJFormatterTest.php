<?php

namespace Tests\Units\Formatters;

use Carbon\Carbon;
use Devesharp\Exceptions\Exception;
use Devesharp\Generators\ControllerGenerator;
use Devesharp\Patterns\Transformer\Transformer;
use Devesharp\Support\Collection;
use Devesharp\Support\Formatters\CPFAndCNPJFormatter;
use Devesharp\Support\Formatters\PhoneFormatter;
use Devesharp\Support\Formatters\PriceFormatter;
use Illuminate\Routing\Controller;
use Tests\Units\Presenter\Mocks\ModelPresenter;
use Tests\Units\Transformer\Mocks\ModelStub;
use Tests\Units\Transformer\Mocks\ModelStub2;
use Tests\Units\Transformer\Mocks\TransformerStub;

class CPFAndCNPJFormatterTest extends \Tests\TestCase
{
    /**
     * @testdox Testando formatação de preço
     */
    public function testPhoneFormatter()
    {
        $this->assertEquals('', format(CPFAndCNPJFormatter::class, null));
        $this->assertEquals('', format(CPFAndCNPJFormatter::class, ''));
        $this->assertEquals('045.521.960-57', format(CPFAndCNPJFormatter::class, '04552196057'));
        $this->assertEquals('65.681.908/0001-53', format(CPFAndCNPJFormatter::class, '65681908000153'));
    }
}
