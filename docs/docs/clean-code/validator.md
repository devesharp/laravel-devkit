---
sidebar_position: 2
---

# Validator

# Devesharp PHP

### Console

Comandos para criar classes rápidas:

```shell script
# Criar todos as classes
php artisan ds:all 

# Criar serviço
php artisan ds:service

# Criar repositório
php artisan ds:repository

# Criar validator
php artisan ds:validator

# Criar Policy
php artisan ds:policy

# Criar transfomer
php artisan ds:transfomer 
```

## Resumo

Essa biblioteca serve como um pattern para criação de novos projetos. É utilizado pela Devesharp para manter o ecosistema de projetos sempre de fácil manutenção para qualquer membro da equipe.

Com base na clean arquitetura, decidimos dividir o projeto em camadas, para ser de fácil manutenção e reutilização.
As camadas são dividas em:

*Controller*: Classe que define o que deve ser feito quando o usuário acessa uma rota e prepara os DataRequests.

*Services*: Classe que contém toda a lógica e regra de negócio da aplicação

*Repositories*: Classe que interaje com o banco de dados (Assim fica de fácil manutenção caso seja necessário trocar o banco de dados).

*Models*: Classe que representa a tabela do banco de dados.

*Transformers*: Classe responsável por tratar os dados para serem enviados para os retornos das rotas.

*Presenters*: Classe responsável por transformar valores de um Model.

*Policies*: Classe responsável por autorizar se um usuário deve ou não fazer a ação.

*DTOs*: Classe responsáveis por validar dados de objetos.

## Validators

**Validators** são classes responsáveis por validar os dados de entrada. São validações são totalmente baseadas no [laravel](https://laravel.com/docs/9.x/validation).
Você deve extender a classe `Validator` nas suas classes de validação. Exemplo:

```php
<?php

namespace Tests\Units\Validators\Mocks;

use Devesharp\Validator\Validator;

class ValidatorExample extends Validator
{
    protected array $rules = [
        'create' => [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => 'numeric|required',
            'active' => 'boolean',
        ],
        'update' => [
            '_extends' => 'create',
            'id' => 'numeric',
        ],
        'search' => [
            'filters.name' => 'string',
            'filters.full_name' => 'string',
        ],
    ];


    public function create(array $data, $requester = null)
    {
        $context = 'create';

        return $this->validate($data, $this->getValidate($context));
    }

    public function update(array $data, $requester = null)
    {
        $context = 'update';

        return $this->validate($data, $this->removeRequiredRules($this->getValidate($context)));
    }

    public function search(array $data, $requester = null)
    {
        return $this->validate($data, $this->getValidateWithSearch('search'));
    }
}
```

E deve ser utilizada na classe de serviço. Exemplo:

```php
<?php

class Service
{
    public function __construct(
        protected \App\Validators\ValidatorExample $validator
    ) {
    }
    
    public function create(array $originalData, $requester = null)
    {
        $data = $this->validator->create($originalData, $requester);
        
        // ...restante da lógica
    }
}
```

### Configurações

#### `additionalProperties`

Por padrão `additionalProperties` é falso e nenhum valor adicional é permitido, você pode alterar isso para resgatar todos os valores que vieram da array.

## Swagger Generator

Para gerar usar 'Swagger Generator', você deve utilizar o trait `Devesharp\Swagger\Traits\SwaggerValidator` no validator.
Esse trait irá habilitar alguns métodos para gerar 'Swagger Generator' de forma dinâmica.