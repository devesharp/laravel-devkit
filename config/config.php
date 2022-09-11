<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Namespaces
    |--------------------------------------------------------------------------
    |
    */
    "namespace" => [
        'Controller' => app_path('App\Modules\{{ModuleName}}\Controller'),
        'Dto' => app_path('App\Modules\{{ModuleName}}\Dto'),
        'Service' => app_path('App\Modules\{{ModuleName}}\Service'),
        'Factory' => app_path('App\Modules\{{ModuleName}}\Resources\Factory'),
        'Model' => app_path('App\Modules\{{ModuleName}}\Resources\Model'),
        'Policy' => app_path('App\Modules\{{ModuleName}}\Policy'),
        'Presenter' => app_path('App\Modules\{{ModuleName}}\Resources\Presenter'),
        'Repository' => app_path('App\Modules\{{ModuleName}}\Resources\Repository'),
        'Docs' => app_path('App\Modules\{{ModuleName}}\Supports\Docs'),
        'Interfaces' => app_path('App\Modules\{{ModuleName}}\Interfaces'),
        'Transformer' => app_path('App\Modules\{{ModuleName}}\Transformer'),
        'Migration' => base_path('database/migrations'),
        'TestRoute' => base_path('tests/Routes/{{ModuleName}}'),
        'TestUnit' => base_path('tests/Units/{{ModuleName}}'),
    ],
    "path" => [
        'Controller' => app_path('Modules/{{ModuleName}}/Controller'),
        'Dto' => app_path('Modules/{{ModuleName}}/Dto'),
        'Service' => app_path('Modules/{{ModuleName}}/Service'),
        'Factory' => app_path('Modules/{{ModuleName}}/Resources/Factory'),
        'Model' => app_path('Modules/{{ModuleName}}/Resources/Model'),
        'Policy' => app_path('Modules/{{ModuleName}}/Policy'),
        'Presenter' => app_path('Modules/{{ModuleName}}/Resources/Presenter'),
        'Repository' => app_path('Modules/{{ModuleName}}/Resources/Repository'),
        'Docs' => app_path('Modules/{{ModuleName}}/Supports/Docs'),
        'Interfaces' => app_path('Modules/{{ModuleName}}/Interfaces'),
        'Transformer' => app_path('Modules/{{ModuleName}}/Transformer'),
        'Migration' => base_path('database/migrations'),
        'TestRoute' => base_path('tests/Routes/{{ModuleName}}'),
        'TestUnit' => base_path('tests/Units/{{ModuleName}}'),
    ],
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
