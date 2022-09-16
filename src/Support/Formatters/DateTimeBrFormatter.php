<?php

namespace Devesharp\Support\Formatters;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;
use MichaelRubel\Formatters\Formatter;

class DateTimeBrFormatter implements Formatter
{

    public function __construct(
        public string|null|CarbonInterface $date = null,
        public string $format = 'Y-m-d H:i:s'
    ) {
        if (! $this->date instanceof CarbonInterface) {
            $this->date = app(Carbon::class)->parse($this->date);
        }
    }

    /**
     * Format the date.
     *
     * @param  Collection  $items
     *
     * @return string
     */
    public function format(Collection $items): string
    {
        return $this->date->timezone('America/Sao_Paulo')->format($this->format);
    }
}
