<?php

namespace Devesharp\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Collection extends \Illuminate\Support\Collection
{
    public function __get($key)
    {
        // Dot notation support.
        if (Str::contains($key, '.') && Arr::has($this->items, $key)) {
            return Arr::get($this->items, $key);
        }

        return $this->items[$key];
    }

    public function __set($key, $value)
    {
        return Arr::set($this->items, $key, $value);
    }

    public function __unset($name)
    {
        if (Str::contains($name, '.')) {
            $this->items = Helpers::arrayExclude($this->items, $name);
        } else {
            unset($this->items[$name]);
        }
    }

    public function __isset($name)
    {
        return Arr::has($this->items, $name);
    }

    public function offsetGet($key): mixed
    {
        // Dot notation support.
        if (Str::contains($key, '.') && Arr::has($this->items, $key)) {
            return Arr::get($this->items, $key);
        }

        return $this->items[$key];
    }

    public function offsetExists($key): bool
    {
        return Arr::has($this->items, $key);
    }

    public function offsetUnset($name): void
    {
        if (Str::contains($name, '.')) {
            $this->items = Helpers::arrayExclude($this->items, $name);
        } else {
            unset($this->items[$name]);
        }
    }

    public function offsetSet($key, $value): void
    {
        Arr::set($this->items, $key, $value);
    }

    public function toArray()
    {
        return $this->items;
    }
}
