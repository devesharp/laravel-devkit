<?php

namespace Devesharp\Support\Formatters;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Devesharp\Support\Helpers;
use Devesharp\Support\Masks;
use Illuminate\Support\Collection;
use MichaelRubel\Formatters\Formatter;

class PriceFormatter implements Formatter
{

    public function __construct(
        public string|int|float|null $price = null,
        public bool $withDecimals = true,
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
        return Masks::priceBr($this->price * 100, $this->withDecimals);
    }
}
