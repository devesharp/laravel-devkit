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

### Notas

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

