<?php

namespace Devesharp\Support\Formatters;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Devesharp\Support\Helpers;
use Devesharp\Support\Masks;
use Illuminate\Support\Collection;
use MichaelRubel\Formatters\Formatter;

class DateTimeISOFormatter implements Formatter
{
    public function __construct(
        public string|null|CarbonInterface $date = null,
        public $formatString = '',
    ) {
        if (! $this->date instanceof CarbonInterface && !empty($this->date)) {
            $this->date = app(Carbon::class)->parse($this->date);
        }
    }

    /**
     * Format the date.
     *
     * @param  Collection  $items
     * @return string
     */
    public function format(Collection $items): string
    {
        if ($this->date instanceof CarbonInterface) {
            if (!empty($this->formatString)) {
                return $this->date->format($this->formatString);
            }
            return $this->date->format('Y-m-d H:i:s');
        }

        return '';
    }
}
