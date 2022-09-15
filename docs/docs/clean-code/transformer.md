---
sidebar_position: 2
---

# Transformer

## Introdução

**Transformers** é a camada responsável por tratar os dados de saida para a web.

Exemplo de definição da classe:

```php title="UsersTransformer.php"
<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\Patterns\Transformer\Transformer;
use App\Model\User;

class UserTransformer extends Transformer
{
    public string $model = User::class;

    public function getDefault($model, $requester = null)
    {
        if (! $model instanceof $this->model) {
            throw new \Exception('Invalid model transform');
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

## Utilização

Para utilizar um transfomer você deve chamar as funções estáticas da classe `Devesharp\Patterns\Transformer\Transformer`:

```php
// Transformar vários models
$users = User::query()->get()->all();
Transformer::collection($users, $this->transformer, 'default', $requester);

// Transformar único model
$user = UserStub::query()->first();
Transformer::item($user, $this->transformer, 'default', $requester)
```

### Contexto

Imagine que você precise retornar saidas diferentes dependendo do usuário que está fazendo a requisição. Para isso você pode passar um contexto para o transformer.

```php
if($user->admin) {
// highlight-next-line
    Transformer::item($user, $this->transformer, 'forAdministration', $requester)
} else {
    Transformer::item($user, $this->transformer, 'default', $requester)
}
```

Ao usar `forAdministration` como contexto, o transformer irá procurar por uma função chamada `getForAdministration`.

```php title="UsersTransformer.php"
public function getForAdministration(
    $model,
    $requester = null,
    $default = []
) {
    return [
        'id' => $model->id,
    ];
}
```

#### Se atente para o terceiro parametro `$default`. 
Em toda função customizada, sua função receberá como terceiro parametro o retorno da função `getDefault()`.

### Relacionamentos

Para facilitar a criação de relacionamentos, você pode utilizar a variável `$loads` para definir os relacionamentos.

```php 
protected array $loads = [
    'user' => [UsersRepository::class, 'user_id', 'alternative_id'],
];
```

1. O primeiro parametro é obrigatório. Sendo o repositório que será utilizado para buscar o relacionamento.
2. O segundo parametro é obrigatório. Sendo a coluna da chave do relacionamento.
3. O terceiro parametro é opcional. Sendo o nome da coluna da tabela do relacionamento. Caso não seja informado, será `id`. (opcional)

Assim, você poderá chamar o relacionamento no transformer pelo mêtodo mágico `getUser`.

```php title="UsersTransformer.php"
public function getDefault(
    $model,
    $requester = null,
    $default = []
) {

    return [
        'id' => $model->id,
        'user_created' => $this->getUser($id),
    ];
}
```

Se `$this->getUser($id)` for chamado com um id que não existia em um relacionamento, o mesmo irá buscar o valor no repositório `UsersRepository::class` na coluna `alternative_id` e então irá salvar em cache.

## Swagger

Para facilitar a documentação da API, você pode utilizar o trait `Devesharp\Swagger\SwaggerTransformer` no transformer. Que irá ajudar a documentação do swagger.

```php title="UsersTransformer.php"
<?php

namespace Tests\Units\Transformer\Mocks;

use Devesharp\Patterns\Transformer\Transformer;

class UserTransformer extends Transformer
{
    use Devesharp\Swagger\SwaggerTransformer;
    
    // Informar o tipo e a descrição da saida
    public string $returnedInfo = [
        'name' => ['string', 'Nome do usuário'],
    ];
}
```