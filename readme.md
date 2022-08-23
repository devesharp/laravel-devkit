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

### API Docs Generator

Essa classe possui funcionalidades para ser usadas na biblioteca Devesharp API Generator, por isso, é importante que você utilize essas funcionalidades.

##### Descrição dos campos

Você pode adicionar descrição das suas keys, passando uma array no seu schema, ao invés de passar apenas a validação.

```php
protected array $rules = [
    'create' => [
        'name' => ['string|max:100|required', 'Nome do usuário'],
        'age' => ['numeric|required', 'Idade do usuário'],
        'active' => ['boolean', 'Ativo/Inativo'],
    ],
];
```

## Transformers

**Transformers** são classes responsáveis por transformar os dados de saida. Deve ser usado para transformar os dados para as respostas das rotas. 

Exemplo de definição:

```php
<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\Patterns\Transformer\Transformer;

class TransformerStub extends Transformer
{
    public string $model = ModelStub::class;

    public function getDefault(
        $model,
        $requester = null
    ) {
        if (! $model instanceof $this->model) {
            throw new \Exception('invalid model transform');
        }
        
        public function getDefault(
        $model,
        $requester = null
    ) {
        if (! $model instanceof $this->model) {
            throw new \Exception('invalid model transform');
        }

        return [
            'id' => $model->id,
            'name' => $model->name,
            'age' => $model->age,
            'user_create' => $model->user_create,
            'updated_at' => (string) $model->updated_at,
            'created_at' => (string) $model->created_at,
        ];
    }
}
```

Para utilizar um transfomer você deve chamar as funções estáticas:

```php
Transformer::collection(ModelStub::query()->get()->all(), $this->transformer, $context, $requester);
Transformer::item(ModelStub::query()->first(), $this->transformer, $context, $requester)
```

Você pode usar variações de contexto, passando o valor `$context` e criando sua respectiva função. Exemplo do context default deve ter uma função chamada getDefault.

```php
Transformer::collection(ModelStub::query()->get()->all(), $this->transformer, 'custom_value', $requester);
Transformer::item(ModelStub::query()->first(), $this->transformer, 'custom_value', $requester);

// Deverá ser criado a seguinte função:

public function getCustomValue(
    $model,
    $requester = null,
    $default = []
) {
    return [
        'id' => $model->id,
    ];
}
```

*Observação:* Todas as funções receberam o valor de transformDefault como terceiro paramêtro.

### Relacionamentos

Para facilitar a criação de relacionamentos, você pode utilizar a variável `$rules` para definir os relacionamentos.

```php 
protected array $loads = [
    // Nome => Repositorio, localKey, foreignKey (default: id)
    'foo' => [RepositoryFooStub::class, 'user_create'],
    // Nome => Repositorio, localKey, foreignKey
    'alternative' => [RepositoryFoo2Stub::class, 'user_create', 'alternative_id'],
];
```

O relacionamento pode ser chamado de duas formas:

##### Com `Transformer::collection`

Quando `Transformer::collection` for chamado, automáticamente será buscado as keys do 2 paramêtro de $loads no repositório escolhido. Exemplo:

```php
    // Load
    protected array $loads = [
        'alternative' => [RepositoryFoo2Stub::class, 'user_create', 'alternative_id'],
    ];
    
    // Call
    Transformer::collection($models, $transformer);
```

Nesse caso, `Transformer::collection` irá criar um array com todas as keys `user_create` que encontrar na array `$models`, e irá buscar IDs no repositório `RepositoryFoo2Stub::class` na coluna `alternative_id` e então salvar em cache.
O valor poderá ser consultado no metodo mágico `$this->getFoo($id)`.

##### Diretamento com `$this->getFoo($id)`

Se chamar `$this->getFoo($id)` sem ter feito o load inicial de relacionamento, o mesmo irá buscar o valor no repositório `RepositoryFooStub::class` na coluna `alternative_id` e então irá salvar em cache.