<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    */
    "namespace" => [
        'controller' => 'App\Modules\{{ModuleName}}\Controllers',
        'dto' => 'App\Modules\{{ModuleName}}\Dtos',
        'service' => 'App\Modules\{{ModuleName}}\Services',
        'factory' => 'App\Modules\{{ModuleName}}\Factories',
        'model' => 'App\Modules\{{ModuleName}}\Models',
        'policy' => 'App\Modules\{{ModuleName}}\Policies',
        'presenter' => 'App\Modules\{{ModuleName}}\Presenters',
        'repository' => 'App\Modules\{{ModuleName}}\Repositories',
        'routeDocs' => 'App\Modules\{{ModuleName}}\Supports\Docs',
        'transformerInterface' => 'App\Modules\{{ModuleName}}\Interfaces',
        'transformer' => 'App\Modules\{{ModuleName}}\Transformers',
        'migration' => 'database/migrations',
        'testRoute' => 'Tests\Routes\{{ModuleName}}',
        'testUnit' => 'Tests\Units\{{ModuleName}}',
    ],
    "path" => [
        'controller' => app_path('Modules/{{ModuleName}}/Controllers'),
        'dto' => app_path('Modules/{{ModuleName}}/Dtos'),
        'service' => app_path('Modules/{{ModuleName}}/Services'),
        'factory' => app_path('Modules/{{ModuleName}}/Factories'),
        'model' => app_path('Modules/{{ModuleName}}/Models'),
        'policy' => app_path('Modules/{{ModuleName}}/Policies'),
        'presenter' => app_path('Modules/{{ModuleName}}/Presenters'),
        'repository' => app_path('Modules/{{ModuleName}}/Repositories'),
        'routeDocs' => app_path('Modules/{{ModuleName}}/Supports/Docs'),
        'transformerInterface' => app_path('Modules/{{ModuleName}}/Interfaces'),
        'transformer' => app_path('Modules/{{ModuleName}}/Transformers'),
        'migration' => base_path('database/migrations'),
        'testRoute' => base_path('tests/Routes/{{ModuleName}}'),
        'testUnit' => base_path('tests/Units/{{ModuleName}}'),
        'api_routes' => base_path('routes/api.php'),
    ],
//    "relations" => [
//        "Users" => [
//            "platform_id" => [
//                "resource" => "Platforms",
//                "field" => "id",
//            ],
//            "type_id" => [
//                "resource" => "UsersTypes",
//                "field" => "id",
//            ],
//        ],
//        "Platforms" => [
//            "system_id" => [
//                "resource" => "System",
//                "field" => "id",
//            ]
//        ],
//    ],
    'commands' => [
        'group_by_name' => true, // Agrupar arquivos por nome
        'snippets' => [
            'unit-tests' => [
                'header-test' => '$userAdmin = Users::factory()->create();',
                'header-namespaces' => 'use \App\Modules\ModuleName\Models\ServiceName;'
            ]
        ]
    ],
    'APIDocs' => [
        'version' => "1.0",
        'name' => "API " . env('name'),
        'description' => "",
        'termsOfService' => "http://example.com/terms/",
        'contact' => [
            'name' => '',
            'url' => '',
            'email' => '',
        ],
        'servers' => [
            'url' => 'dev',
            'description' => 'https://dev.api.com.br',
        ],
        'save_file' => 'api-docs.yml',
        'refs' => [
            // Ref::class
        ]
    ]
];
