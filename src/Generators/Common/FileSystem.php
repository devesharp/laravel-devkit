<?php

namespace Devesharp\Generators\Common;


use Illuminate\Support\Str;

class FileSystem
{
    public array $three = [];

    public function writeFile(string $path, $content = '', $options = null) {
        $this->three[$path] = $content;
    }

    public function readFile(string $path) {
//        file_get_contents($path);
        return '';
    }

    public function render() {
        foreach ($this->three as $path => $content) {
            if (!file_exists(dirname($path))) {
                mkdir(dirname($path), 0777, true);
            }

            file_put_contents($path, $content);
        }
    }

    public function getFiles(): array {
        return collect($this->three)->keys(fn($k) => $k)->toArray();
    }
}
