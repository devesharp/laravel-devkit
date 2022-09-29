---
sidebar_position: 1
---

#  Arquivo para geração de módulo

Esses tópicos descrevem a versão 3 do formato de arquivo do Compose. Esta é a versão mais recente.

## module
Nome do grupo do módulo (Normalmente o mesmo que o `nome do módulo`)

## name
Nome do módulo

## components
### withController
Se deve criar um controller para esse módulo

### withDto
Se deve criar um DTO para esse módulo

### withService
Se deve criar um serviço para esse módulo

### withFactory
Se deve criar uma factory para esse módulo

### withModel
Se deve criar um model para esse módulo

### withPolicy
Se deve criar uma policy para esse módulo

### withPresenter
Se deve criar um presenter para esse módulo

### withRepository
Se deve criar um repositório para esse módulo

### withRouteDocs
Se deve criar uma documentação de rota para esse módulo

### withTransformerInterface
Se deve criar uma interface de transformador para esse módulo

### withTransformer
Se deve criar um transformador para esse módulo

### withTestRoute
Se deve criar um teste de rota para esse módulo

### withTestUnit
Se deve criar um teste unitário para esse módulo

## fields
Campos que devem ser gerados para esse módulo

### campo: dbType
Tipo de coluna

    char
    string
    text
    tinyText
    mediumText
    integer
    longText
    tinyInteger
    mediumInteger
    smallInteger
    bigInteger
    float
    double
    boolean
    decimal
    enum
    json
    set
    jsonb
    dateTime
    date
    dateTimeTz
    timeTz
    time
    timestamp
    year
    timestampTz
    binary
    foreignUuid
    uuid
    ipAddress
    geometry
    macAddress
    point
    polygon
    lineString
    geometryCollection
    multiLineString
    multiPoint
    multiPolygon
    multiPolygonZ
    computed

### campo: rules
É a regra de validação do campo para ser usado no Dto, o mesmo visto em [laravel](https://laravel.com/docs/9.x/validation). Por padrão, será adicionado o valor correspondente ao dbType.

Ex:
```yml
name: Users
fields:
    letters:
        dbType: "char"
        required: true
        rules: 
          - 'limit:200'
```    
Nessa caso o validator do campo letters ficará 'string|required|limit:200'
### campo: searchable
Se esse campo é buscável, ao definir como true. Será adicionado essa key no $filters no layer serviço e no Dto de busca.
```yml
name: Users
fields:
    age:
        dbType: "int"
        searchable: true
```

Então:

```php title="UsersService.php"
class UsersService extends Service {
// ...
 
    public array $filters = [
        'age' => [
            'column' => 'age',
            'filter' => ServiceFilterEnum::whereInt,
        ],
    ];

// ...
```

```php title="SearchUsersDto.php"
class SearchDtoStub extends AbstractDto
{
    protected function configureValidatorRules(): array
    {
        $this->extendRules(SearchTemplateDto::class);

        return [
            'filters.age' => new Rule('numeric'),
        ];
    }
}
```

### campo: sort
Se deve ser usado para ordenar. Será sim, será adicionado essa key no $sorts no layer serviço.

```php title="UsersService.php"
class UsersService extends Service {
// ...
 
    public array $sort = [
            'age' => [
                'column' => 'users.age',
            ],
        ];
    ];

// ...
```

### campo: primary
Campo primário na geração da tabelas. Se sim, no migration será adicionado `->primary()`.

### campo: dto
Deve ser adiciona no layer de Dto?

### campo: transformer
Deve ser adiciona na sáida do transformer?

### campo: description
Descrição do campo, será adicionado no Dto para ser usado na geração de documentação do swagger.

### campo: default
Valor padrão que será usado no banco de dados ao gerar migração.

### campo: relation
Criar relação de tabelas.

```yml
name: Posts
fields:
    user_id_created:
        dbType: "int"
        relation:
          relationType: mt1
          table: Users
          id: id
          
```

#### relationType
Tipo de relação
`1t1` = One to One
`1tm` = One to Many
`mt1` = Many to One
`mtm` = Many to Many
`hmt` = Has Many Through
#### table
Nome da tabela que irá fazer a relação
#### id
Id da tabela que irá fazer a relação

### campo: valueOnSearch
Valores usados ao buscar recurso

### campo: valueOnCreate
Saber se esse campo deve ser resgatado do usuário na criação do documento. Como exemplo o campo user_id_created.
Ex:
```yml
name: Posts
fields:
    user_id_created:
      dbType: "int"
      relation:
        relationType: mt1
        table: Users
        id: id
      valueOnCreate:
        getByUser: 'id'
    platform_id:
      dbType: "int"
      relation:
        relationType: mt1
        table: Platforms
        id: id
      valueOnCreate:
        getByUser: 'platform_id'
    published_at:
      dbType: "timestamp"
      valueOnCreate:
        value: 'now'
```

Então será adicionado:
```php title="UsersService.php"
class UsersService extends Service {
// ...
 
    public function treatment(
        $requester,
        Collection $requestData,
        $currentModel,
        string $method
    ) {
        if ($method == 'update') {
            return $requestData;
        } else if ($method == 'create') {
            // Adicionar campos, diretamento do usuário
            // highlight-next-line
            $requestData['user_id_created'] = $requester->id;
            // highlight-next-line
            $requestData['platform_id'] = $requester->platform_id;
            
            return $requestData;
        }

        return $requestData;
    }

// ...
```

### campo: nullable
Se campo pode ser null no banco de dados ao gerar migração. `->nullable()`

