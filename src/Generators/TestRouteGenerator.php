<?php

namespace Devesharp\Generators;


use Devesharp\Generators\Common\BaseGeneratorAbstract;
use Devesharp\Generators\Common\TemplateGenerator;
use Illuminate\Support\Str;

class TestRouteGenerator extends TemplateGenerator
{
    public string $resourceType = 'testRoute';

    public function getTemplateFilename(): string
    {
        return 'devesharp-generators::Tests/test-route-default';
    }

    function loadImports(): void {
        $this->templateData->addImport('{{ $routeDocsNamespace }}{{ $resourceName }}RouteDoc');
        $this->templateData->addImport('{{ $dtoNamespace }}\Create{{ $resourceName }}Dto');
        $this->templateData->addImport('{{ $dtoNamespace }}\Search{{ $resourceName }}Dto');
        $this->templateData->addImport('{{ $dtoNamespace }}\Update{{ $resourceName }}Dto');
        $this->templateData->addImport('{{ $modelNamespace }}\{{ $resourceName }}');
        $this->templateData->addImport('{{ $userModelNamespace }}\Users');
        $this->templateData->addImport('Illuminate\Support\Facades\Hash');
        $this->templateData->addImport('Tests\TestCase');
        $this->templateData->addImport('PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth');
    }

    public function getData(): array
    {
        $relations = config('devesharp_generator.relations', []);
        $userVariable = 'user';
        $headerFnTest = '';
        $useNamespace = '';

        $modelNamespace = $this->replaceString($this->config->getNamespace('model'));

        if (!empty($relations['Users'])) {
            foreach ($relations['Users'] as $key => $field) {
                $headerFnTest .= '        $' . Str::camel($field['resource']) . ' = ' . $field['resource'] . '::factory()->create();' . PHP_EOL;
                $useNamespace .= 'use ' . $modelNamespace . '\\' . $field['resource'] . ';' . PHP_EOL;
            }
            $headerFnTest .= '        $user = User::factory([' . PHP_EOL;
            foreach ($relations['Users'] as $key => $field) {
                $headerFnTest .= '            \'' . $key . '\' => $' . Str::camel($field['resource']) . '->id,' . PHP_EOL;
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
