<?php

return [
    'commands' => [
        'group_by_name' => true, // Agrupar arquivos por nome
        'snippets' => [
            'unit-tests' => [
                'header' => '$userAdmin = Users::factory()->create();',
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
