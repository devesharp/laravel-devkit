---
sidebar_position: 1
---

# Introdução

Após anos de desenvolvimento de muitos projetos, percebemos que acabavamos recriando muita coisa, novos desenvolvedores tinha uma curva de aprendizagem alta, já que o código apesar de usarmos o laravel não tinha um padrão de código fácil para podermos passarmos para os novos desenvolvedores. Então decidimos separar e padronizar nosso código em diversas layers, baseado-nós na arquitetura clean code, organizamos nosso código em um determinado estrura separando em layers para ser organizado, padronizado, legível, testável e escalável. Dessa forma, podemos resgatar um determinado modulo e passar para outro projeto de forma rápida, testável e eficaz, já que cada layer é isolada é responsável por ela mesmo.

## Layers

**Controller**: Classe que define o que deve ser feito quando o usuário acessa uma rota e prepara os DataRequests.

**Services**: Classe que contém toda a lógica e regra de negócio da aplicação

**Repositories**: Classe que interaje com o banco de dados (Assim fica de fácil manutenção caso seja necessário trocar o banco de dados).

**Models**: Classe que representa a tabela do banco de dados.

**Transformers**: Classe responsável por tratar os dados para serem enviados para os retornos das rotas.

**Presenters**: Classe responsável por transformar valores de um Model.

**Policies**: Classe responsável por autorizar se um usuário deve ou não fazer a ação.

**DTOs**: Classe responsáveis por validar dados de objetos.

## Organização de pastas

Cada layer possui sua determinada pasta, agrupada por módulos.

```shell script
my_application
├── app
│   └── Modules
│       └── Users
│           ├── Resources
│       ├── index.js
│       ├── _ignored.js
│       ├── _ignored-folder
│       │   ├── Component1.js
│       │   └── Component2.js
│       └── support
│           ├── index.js
│           └── styles.module.css
.
```

:::info

Módulos são agrupamentos de layers, que podem ser reutilizados em outros projetos. Um mesmo módulo pode ter diversos layers.

Exemplo:

Módulo de Usuários
Pode ter o serviço de usuários
Pode ter o serviço de permissão de usuários
Pode ter o serviço de configurações dos usuários

:::