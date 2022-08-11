<?php

namespace Devesharp\Testing;

use Carbon\Carbon;
use Devesharp\APIDocs\TestDocsGenerate;
use Devesharp\Support\Helpers;

trait TestCase
{
    /**
     * Verifica se todas as keys da $array são iguais da $leftArray
     * $array pode ter mais de keys que $leftArray, só é necessário ter as de $leftArray
     *
     * @param $leftArray
     * @param $array
     * @param array $exclude
     */
    function assertEqualsArrayLeft($leftArray, $array, $exclude = []) {
        $newLeftArray = Helpers::arrayExclude($leftArray, $exclude);

        foreach ($newLeftArray as $key => $item) {
            if ($item instanceof \DateTime) {
                $item = Carbon::make($item);
                $array[$key] = Carbon::make($array[$key]);
            }

            if (is_array($item)) {
                $item = json_encode($item);
            }
            if (is_array($array[$key])) {
                $array[$key] = json_encode($array[$key]);
            }

            $this->assertEquals($item, $array[$key], $key);
        }
    }

    function withPost($http) {
        return (new TestDocsGenerate('', 'post', $http, $this));
    }

    function withGet($http) {
        return (new TestDocsGenerate('', 'get', $http, $this));
    }

    function withDelete($path) {
        return (new TestDocsGenerate('', 'delete', $path, $this));
    }

    function withPut($path) {
        return (new TestDocsGenerate('', 'put', $path, $this));
    }

    private function treatmentHttpArgs($args) {

        $uriForTest = $args['uri'];

        if (!empty($args['params'] )) {
            foreach ($args['params'] as $param) {
                $uriForTest = str_replace(':' . $param['name'], $param['value'], $uriForTest);
            }
        }

        if (!empty($args['queries'] )) {
            $query = [];
            foreach ($args['queries'] as $param) {
                $query[] = $param['name'] . '=' . $param['value'];
            }
            $uriForTest = $uriForTest . '?' . implode('&', $query);
        }

        if ($args['uri'][0] != "/") {
            $args['uri'] = '/' . $args['uri'];
        }

        $args['uriForTest'] = $uriForTest;

        return $args;
    }
}
