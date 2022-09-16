---
sidebar_position: 2
---

# Instalação

Via composer:

```bash
composer require devesharp/laravel-devit
```

### Laravel > 5.5
Se você executar o pacote no Laravel 5.5+, a descoberta automática de pacotes cuida da mágica de adicionar o provedor de serviços. Então não é necessário fazer mais nada.

### Laravel < 5.5
Se você não executa o Laravel 5.5 (ou superior), adicione os seguintes provedores de serviços em config/app.php:

```bash
Devesharp\Generators\Provider\GeneratorsProvider::class,
Devesharp\SwaggerGenerator\Providers\SwaggerGeneratorProvider::class,
```

### Sobreescrevendo arquivos de configuração

Você pode sobre escrever os arquivos de configuração, rodando:

```bash
php artisan vendor:publish --tag="devesharp-laravel-devit-config"
```

Importe 