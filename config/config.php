<?php

return [
    'APIDocs' => [
        'name' => "API " . env('name'),
        'description' => "",
        'version' => "1.0",
        'servers' => [
            'url' => 'dev',
            'description' => 'https://dev.api.com.br',
        ],
        'save_file' => 'api-docs.yml'
    ]
];
