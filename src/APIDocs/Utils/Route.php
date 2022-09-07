<?php

namespace Devesharp\APIDocs\Utils;

class Route
{
    public $method = '';
    public int $statusCode = 200;
    public string $path = '';
    public array $tags = [];
    public string $variationName = '';
    public string $variationDescription = '';
    public string $summary = '';
    public string $bodyType = 'application/json'; // pode usar multipart/form-data
    public string $description = '';
    public array $externalDocs = [];
    public bool $deprecated = false;
    public array $parameters = [];
    public array $body = [];
    public array $bodyComplete = [];
    public array $bodyRequired = [];
    public array $bodyDescription = [];
    public array $response = [];
    public string $descriptionResponse = '';
    public array $security = [];
}
