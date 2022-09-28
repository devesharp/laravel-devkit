<?php

namespace Tests\Units\Generators;

use Tests\TestCase;

abstract class TestCaseGenerator extends TestCase
{
    function assertTemplate($relativeDir, $render) {
        $dirname = __DIR__ . '/mocks/' . $relativeDir;

        file_put_contents($dirname, $render);

        $this->assertEquals(file_get_contents($dirname), $render);
    }
}