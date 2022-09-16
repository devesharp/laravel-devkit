<?php

namespace Devesharp\Support\Formatters;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Devesharp\Support\Helpers;
use Devesharp\Support\Masks;
use Illuminate\Support\Collection;
use MichaelRubel\Formatters\Formatter;

class CNPJFormatter implements Formatter
{

    public function __construct(
        public string|null $CNPJ = null
    ) {}

    /**
     * Format the date.
     *
     * @param  Collection  $items
     *
     * @return string
     */
    public function format(Collection $items): string
    {
        return Masks::CNPJ($this->CNPJ);
    }
}
