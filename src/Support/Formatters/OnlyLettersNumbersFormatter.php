<?php

namespace Devesharp\Support\Formatters;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Devesharp\Support\Helpers;
use Devesharp\Support\Masks;
use Illuminate\Support\Collection;
use MichaelRubel\Formatters\Formatter;

class OnlyLettersNumbersFormatter implements Formatter
{

    public function __construct(
        public string|null $string = null
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
        return preg_replace("/[^a-zA-Z0-9]+/", "", $this->string);
    }
}
