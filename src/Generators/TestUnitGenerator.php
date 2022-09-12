<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Illuminate\Support\Str;

class TestUnitGenerator extends BaseGeneratorAbstract
{

    public string $resourceType = 'testUnit';

    public function getFile(): string
    {
        return 'devesharp-generators::Tests/test-unit-default';
    }

    public function getData()
    {
        $relations = config('devesharp_generator.relations', []);
        $userVariable = 'user';
        $headerFnTest = '';
        $useNamespace = '';

        $modelNamespace = $this->replaceNameSpace($this->config->getNamespace('model'));

        if (!empty($relations['Users'])) {
            foreach ($relations['Users'] as $key => $field) {
                $headerFnTest .= '        $' . Str::singular(Str::camel($field['resource'])) . ' = ' . $field['resource'] . '::factory()->create();' . PHP_EOL;
                $useNamespace .= 'use ' . $modelNamespace . '\\' . $field['resource'] . ';' . PHP_EOL;
            }
            $headerFnTest .= '        $user = User::factory([' . PHP_EOL;
            foreach ($relations['Users'] as $key => $field) {
                $headerFnTest .= '            \'' . $key . '\' => $' . Str::singular(Str::camel($field['resource'])) . '->id,' . PHP_EOL;
            }
            $headerFnTest .= '        ])->create();';
        } else {
            $headerFnTest .= '        $user = Users::factory()->create();';
        }

        return [
            'headerFnTest' => $headerFnTest,
            'userVariable' => $userVariable,
            'useNamespace' => $useNamespace,
        ];
    }
}
