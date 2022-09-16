---
sidebar_position: 2
---

# Dto

**Dtos** são classes responsáveis por validar os dados de entrada. As validações são totalmente baseadas no [laravel](https://laravel.com/docs/9.x/validation).

Exemplo de definição da classe:

```php title="CreateUsersDto.php"
<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class CreateUserDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        return [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => ['numeric|required', 'Idade'],
            'active' => ['boolean', 'Ativo' ],
        ];
    }
}
```

## Utilização

Para utilizar um Dto basta chamar o metodo estático `make` com os dados:

```php
CreateUserDto::make(['name' => 'Devesharp', 'age' => 10, 'active' => true]); // Successo
CreateUserDto::make([]); // Erro: O campo Nome é obrigatório.
CreateUserDto::make(request()->all()); // Erro: O campo Nome é obrigatório.
```

### Valores adicionais

Por padrão, qualquer valor que não esteja no configureValidatorRules será ignorado, porém você reter esses valores adicionais, apenas definindo `$additionalProperties`:

```php title="CreateUsersDto.php"
<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class CreateUserDto extends AbstractDto
{
// highlight-next-line
    protected bool $additionalProperties = false;

    protected function configureValidatorRules(): array
    {
        return [
            'name' => ['string|max:100|required', 'Nome'],
            'age' => ['numeric|required', 'Idade'],
            'active' => ['boolean', 'Ativo' ],
        ];
    }
}
```

### Extendendo Dto

É possível extender um Dto, apenas usando a função `extendRules`:

```php title="UpdateUsersDto.php"
<?php

namespace Tests\Units\Dto\Mocks;

use Devesharp\Patterns\Dto\AbstractDto;

class UpdateUsersDto extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        // highlight-next-line
        $this->extendRules(CreateUsersDto::class);

        return [
            'active' => null, // remove active das regras
        ];
    }
}

```

:::info

Algumas vezes, você vai querer que algum valor extendido seja ignorado, para isso, basta definir a regra como `null`.

:::
