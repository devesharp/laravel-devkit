<?php

namespace Devesharp\Testing;

use Carbon\Carbon;
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

    function withPost($args, $ignoreDocs = false) {
        $args = $this->treatmentHttpArgs($args);
        $args['method'] = 'post';
        $args['summary'] = $args['name'];
        $args['context'] = $args['validatorMethod'] ?? null;

        $name = $args['name'];
        $uri = $args['uri'];
        $data = $args['data'] ?? [];
        $headers = $args['headers'] ?? [];
        $validator = $args['validatorClass'] ?? null;
        $validatorMethod = $args['validatorMethod'] ?? null;

        $response = $this->post($args['uriForTest'], $data, $headers);
        $responseData = json_decode($response->getContent(), true);


        $args['response'] = [
            'status' => $response->getStatusCode(),
            'body' => $responseData,
            'description' => $args['response']['description'] ?? '',
            'bodyRequired' => $args['response']['bodyRequired'] ?? [],
            'ignoreBody' => $args['response']['ignoreBody'] ?? [],
        ];

        if (class_exists($validator) && !empty($validatorMethod)) {
            $args['body'] = app($validator)->convertValidatorToData($validatorMethod, $data);
            $args['bodyRequired'] = app($validator)->getRequireds($validatorMethod);
            $args['bodyDescription'] = app($validator)->getDescriptions($validatorMethod);
        } else {
            $args['body'] = $data;
        }

        $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
        $apiDocs->addRoute($args);

        return $response;
    }

    function withGet($args, $ignoreDocs = false) {
        $args = $this->treatmentHttpArgs($args);
        $args['method'] = 'get';
        $args['summary'] = $args['name'];
        $args['context'] = $args['validatorMethod'] ?? null;

        $uri = $args['uri'];
        $headers = $args['headers'] ?? [];
        $validator = $args['validatorClass'] ?? null;
        $validatorMethod = $args['validatorMethod'] ?? null;

        $response = $this->get($args['uriForTest'], $headers);
        $responseData = json_decode($response->getContent(), true);


        $args['response'] = [
            'status' => $response->getStatusCode(),
            'body' => $responseData,
            'description' => $args['response']['description'] ?? '',
            'bodyRequired' => $args['response']['bodyRequired'] ?? [],
            'ignoreBody' => $args['response']['ignoreBody'] ?? [],
        ];


        $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
        $apiDocs->addRoute($args);

        return $response;
    }

    function withDelete($args, $ignoreDocs = false) {
        $args = $this->treatmentHttpArgs($args);
        $args['method'] = 'delete';
        $args['summary'] = $args['name'];
        $args['context'] = $args['validatorMethod'] ?? null;

        $headers = $args['headers'] ?? [];
        $data = $args['data'] ?? [];
        $validator = $args['validatorClass'] ?? null;
        $validatorMethod = $args['validatorMethod'] ?? null;

        $response = $this->delete($args['uriForTest'], $data, $headers);
        $responseData = json_decode($response->getContent(), true);

        $args['response'] = [
            'status' => $response->getStatusCode(),
            'body' => $responseData,
            'description' => $args['response']['description'] ?? '',
            'bodyRequired' => $args['response']['bodyRequired'] ?? [],
            'ignoreBody' => $args['response']['ignoreBody'] ?? [],
        ];

        if (class_exists($validator) && !empty($validatorMethod)) {
            $args['body'] = app($validator)->convertValidatorToData($validatorMethod, $data);
        } else {
            $args['body'] = $data;
        }

        $apiDocs = \Devesharp\APIDocs\APIDocsCreate::getInstance();
        $apiDocs->addRoute($args);

        return $response;
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
