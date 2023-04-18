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
        public string|null|CarbonInterface $date = null
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
            return $this->date->utc()->toIso8601String(true);
        }

        return '';
    }
}
