<?php

namespace Tests\Units\SwaggerGenerator;

use Devesharp\Console\Commands\MakeService;
use Devesharp\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\Units\SwaggerGenerator\Mocks\ValidatorStubWithGenerator;

class SwaggerUtilsTest extends \Tests\TestCase
{
    /**
     * @testdox converter data para schema swagger - string
     */
    public function testConvertDataToSchemaString()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->dataToSchema([
            'key_string' => 'string',
        ], true, ['key_string'], ['key_string' => 'Example String', '' => 'Example Data']);

        $this->assertEquals([
            'type' => 'object',
            'description' => 'Example Data',
            'properties' => [
                'key_string' => [
                    'type' => 'string',
                    'example' => 'string',
                    'description' => 'Example String',
                ],
            ],
            'required' => ['key_string'],
        ], $schema);
    }

    /**
     * @testdox converter data para schema swagger - bool
     */
    public function testConvertDataToSchemaBoolean()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->dataToSchema([
            'key_string' => false,
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'key_string' => [
                    'type' => 'boolean',
                ],
            ]
        ], $schema);
    }

    public function testConvertDataToSchemaNumber()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->dataToSchema([
            'key_integer' => 10,
            'key_double' => 10.10,
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'key_integer' => [
                    'type' => 'integer',
                    'format' => 'int64'
                ],
                'key_double' => [
                    'type' => 'number',
                    'format' => 'double'
                ],
            ]
        ], $schema);
    }

    public function testConvertDataToSchemaSimpleArray()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->dataToSchema([
            'array_string' => ['10'],
            'array_integer' => [10],
            'array_double' => [10.10],
            'array_mixed' => [1,2,3, '10'],
        ], false, ['array_mixed']);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'array_string' => [
                    'type' => 'array',
                    'items' => ['type' => 'string']
                ],
                'array_integer' => [
                    'type' => 'array',
                    'items' => ['type' => 'integer', 'format' => 'int64']
                ],
                'array_double' => [
                    'type' => 'array',
                    'items' => ['type' => 'number', 'format' => 'double']
                ],
                'array_mixed' => [
                    'type' => 'array',
                    'items' => [
                        'anyOf' => [
                            [
                                'type' => 'integer',
                                'format' => 'int64'
                            ],
                            ['type' => 'string'],
                        ]
                    ]
                ],
            ],
            'required' => ['array_mixed'],
        ], $schema);
    }

    public function testConvertDataToSchemaSimpleArrayObject()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->dataToSchema([
            'array_object' => [
                [
                    'key_string' => 'string',
                    'key_integer' => 10,
                ],
                [
                    'key_string' => 'string',
                    'key_integer' => 10,
                ],
            ],
        ]);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'array_object' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'key_string' => [
                                'type' => 'string',
                            ],
                            'key_integer' => [
                                'type' => 'integer',
                                'format' => 'int64'
                            ],
                        ]
                    ]
                ],
            ]
        ], $schema);
    }

    public function testConvertDataToSchemaSimpleArrayObjectWithExample()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->dataToSchema([
            'array_object' => [
                [
                    'key_string' => 'string',
                    'key_integer' => 10,
                ]
            ],
        ], true);

        $this->assertEquals([
            'type' => 'object',
            'properties' => [
                'array_object' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'key_string' => [
                                'type' => 'string',
                                'example' => 'string',
                            ],
                            'key_integer' => [
                                'type' => 'integer',
                                'format' => 'int64',
                                'example' => 10,
                            ],
                        ]
                    ]
                ],
            ]
        ], $schema);
    }

    /**
     * @testdox converter data para schema swagger com valor obrigatórios - array de objeto
     */
    public function testConvertDataToSchemaRequiredItemsArrayObject()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->addRequiredToSchema([
            'type' => 'object',
            'properties' => [
                'array_object' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'key_string' => [
                                'type' => 'string',
                                'example' => 'string',
                            ],
                            'key_integer' => [
                                'type' => 'integer',
                                'format' => 'int64',
                                'example' => 10,
                            ],
                        ]
                    ]
                ],
            ]
        ], [
            "array_object",
            // Redundância para testar se o campo é obrigatório
            "array_object.*.key_string",
            "array_object.0.key_string",
            "array_object.1.key_string",
        ]);

        $this->assertEquals($schema, [
            'type' => 'object',
            'required' => ['array_object'],
            'properties' => [
                'array_object' => [
                    'type' => 'array',
                    'items' => [
                        'type' => 'object',
                        'properties' => [
                            'key_string' => [
                                'type' => 'string',
                                'example' => 'string',
                            ],
                            'key_integer' => [
                                'type' => 'integer',
                                'format' => 'int64',
                                'example' => 10,
                            ],
                        ],
                        'required' => [
                            'key_string'
                        ]
                    ]
                ],
            ]
        ]);
    }

    /**
     * @testdox converter data para schema swagger com valor obrigatórios - objeto
     */
    public function testConvertDataToSchemaRequiredItemsObject()
    {
        $swaggerGenerator = new \Devesharp\SwaggerGenerator\Generator();

        $schema = $swaggerGenerator->addRequiredToSchema([
            'type' => 'object',
            'properties' => [
                'item_object' => [
                    'type' => 'object',
                    'properties' => [
                        'key_string' => [
                            'type' => 'string',
                            'example' => 'string',
                        ],
                        'key_integer' => [
                            'type' => 'integer',
                            'format' => 'int64',
                            'example' => 10,
                        ],
                    ]
                ],
            ]
        ], [
            "item_object",
            "item_object.key_string",
        ]);

        $this->assertEquals($schema, [
            'type' => 'object',
            'required' => ['item_object'],
            'properties' => [
                'item_object' => [
                    'type' => 'object',
                    'properties' => [
                        'key_string' => [
                            'type' => 'string',
                            'example' => 'string',
                        ],
                        'key_integer' => [
                            'type' => 'integer',
                            'format' => 'int64',
                            'example' => 10,
                        ],
                    ],
                    'required' => ['key_string']
                ],
            ]
        ]);
    }


}
