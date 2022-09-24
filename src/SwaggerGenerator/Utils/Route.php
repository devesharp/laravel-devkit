<?php

namespace Devesharp\SwaggerGenerator\Utils;

class Route
{
    /**
     * @var string Método da rota
     */
    public string $method = 'GET';

    /**
     * @var int Código de retorno da rota
     */
    public int $statusCode = 200;

    /**
     * @var string Caminho da rota
     */
    public string $path = '';

    /**
     * @var array Tags Swagger para organização da documentação
     */
    public array $tags = [];

    /**
     * É possivel que na mesma rota, o usuário possa realizar ações diferentes, que exigem um corpo de requisição
     * diferentes. Por exemplo, um usuário pode criar um pagamento na mesma rota para pix, boleto, cartão de crédito, etc.
     * Ex Cartão de crédito: POST /payments
     * {type: credit_card, amount: 100, card_number: 123456789, card_name: Fulano, card_cvv: 123, card_expiration: 2021-12-31}
     * Ex Boleto: POST /payments
     * {type: boleto, amount: 100, boleto_expiration: 2021-12-31}
     *
     * Então você pode usar variationName e variationDescription para diferenciar essas ações.
     * Colocando um titulo e uma descição na request e no response.
     *
     * @var string Descrição do corpo da requisição e resposta
     */
    public string $variationName = '';

    /**
     * @var string Descrição do corpo da requisição e resposta
     */
    public string $variationDescription = '';

    /**
     * @var string Título da rota
     */
    public string $title = '';

    /**
     * @var string Tipo de conteúdo da requisição
     */
    public string $bodyType = 'application/json'; // pode usar multipart/form-data

    /**
     * @var string Descricao da rota
     */
    public string $description = '';

    /**
     * @var array Documentação externa
     */
    public array $externalDocs = [];

    /**
     * @var bool Indica se a rota está depreciada
     */
    public bool $deprecated = false;

    /**
     * @var array Parâmetros do path
     */
    public array $parameters = [];

    /**
     * Esse body é gerado pelo usuário ao realizar o teste do laravel
     *
     * @var array Parâmetros do body para enviar no teste da requisição do laravel
     */
    public array $body = [];

    /**
     * Esse body é gerado automaticamente pelo Dto, e é usado para gerar a documentação
     * É adicionado todos os valores que existem no Dto e que não foram passados no $body
     *
     * @var array Parâmetros do body com todos os valores mockados
     */
    public array $bodyComplete = [];

    /**
     * @var array Parâmetros do body que são obrigatórios
     */
    public array $bodyRequired = [];

    /**
     * @var array Descricao dos parâmetros do body
     */
    public array $bodyDescription = [];

    /**
     * @var array Parâmetros do body que são enums
     */
    public array $bodyEnum = [];

    /**
     * @var array Resposta da rota
     */
    public array $response = [];

    /**
     * @var string Descricao da resposta da rota
     */
    public string $descriptionResponse = '';

    /**
     * @var array Segurança utilizada na rota
     */
    public array $security = [];


    /**
     * @param string $method
     */
    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @param string $bodyType
     */
    public function setBodyType(string $bodyType): void
    {
        $this->bodyType = $bodyType;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags): void
    {
        $this->tags = $tags;
    }

    /**
     * @param string $description
     * @param string $
     * @param $url
     * @return void
     */
    public function setExternalDocs(string $description, string $url): void
    {
        $this->externalDocs = [
            "description" =>  $description,
            "url" =>  $url,
        ];
    }

    /**
     * @param array $body
     */
    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    /**
     * @param array $response
     */
    public function setResponse(array $response): void
    {
        $this->response = $response;
    }

    /**
     * @param array $security
     */
    public function setSecurity(array $security): void
    {
        $this->security = $security;
    }

    /**
     * @param array $bodyRequired
     */
    public function setBodyRequired(array $bodyRequired): void
    {
        $this->bodyRequired = $bodyRequired;
    }

    /**
     * @param array $parameters
     */
    public function setParameters(array $parameters): void
    {
        $this->parameters = $parameters;
    }

    /**
     * @param bool $deprecated
     */
    public function setDeprecated(bool $deprecated = true): void
    {
        $this->deprecated = $deprecated;
    }

    /**
     * @param string $title
     * @param string $description
     * @return void
     */
    public function setTitle(string $title, string $description): void
    {
        $this->title = $title;
        $this->description = $description;
    }
    /**
     * Variação do request e response
     *
     * @param $name
     * @param $description
     * @return void
     */
    function setVariation($name, $description): void {
        $this->variationName = $name;
        $this->variationDescription = $description;
    }
}
