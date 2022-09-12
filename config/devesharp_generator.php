<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    */
    "namespace" => [
        'controller' => 'App\Modules\{{ModuleName}}\Controller',
        'dto' => 'App\Modules\{{ModuleName}}\Dto',
        'service' => 'App\Modules\{{ModuleName}}\Service',
        'factory' => 'App\Modules\{{ModuleName}}\Resources\Factory',
        'model' => 'App\Modules\{{ModuleName}}\Resources\Model',
        'policy' => 'App\Modules\{{ModuleName}}\Policy',
        'presenter' => 'App\Modules\{{ModuleName}}\Resources\Presenter',
        'repository' => 'App\Modules\{{ModuleName}}\Resources\Repository',
        'routeDocs' => 'App\Modules\{{ModuleName}}\Supports\Docs',
        'transformInterface' => 'App\Modules\{{ModuleName}}\Interfaces',
        'transformer' => 'App\Modules\{{ModuleName}}\Transformer',
        'migration' => 'database/migrations',
        'testRoute' => 'Tests\Routes\{{ModuleName}}',
        'testUnit' => 'Tests\Units\{{ModuleName}}',
    ],
    "path" => [
        'controller' => app_path('Modules/{{ModuleName}}/Controller'),
        'dto' => app_path('Modules/{{ModuleName}}/Dto'),
        'service' => app_path('Modules/{{ModuleName}}/Service'),
        'factory' => app_path('Modules/{{ModuleName}}/Resources/Factory'),
        'model' => app_path('Modules/{{ModuleName}}/Resources/Model'),
        'policy' => app_path('Modules/{{ModuleName}}/Policy'),
        'presenter' => app_path('Modules/{{ModuleName}}/Resources/Presenter'),
        'repository' => app_path('Modules/{{ModuleName}}/Resources/Repository'),
        'routeDocs' => app_path('Modules/{{ModuleName}}/Supports/Docs'),
        'transformInterface' => app_path('Modules/{{ModuleName}}/Interfaces'),
        'transformer' => app_path('Modules/{{ModuleName}}/Transformer'),
        'migration' => base_path('database/migrations'),
        'testRoute' => base_path('tests/Routes/{{ModuleName}}'),
        'testUnit' => base_path('tests/Units/{{ModuleName}}'),
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
