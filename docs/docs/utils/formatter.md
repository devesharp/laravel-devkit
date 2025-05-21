---
sidebar_position: 1
---

# Formatter

Para facilitar a formatação de dados, é usado a biblioteca [Laravel Formatters](https://github.com/michael-rubel/laravel-formatters) que permite criar formatações de dados de forma simples e rápida com classes.


## Como usar?

Apenas crie uma classe que implemente a interface `FormatterInterface` e defina o método `format` que recebe o dado a ser formatado e retorna o dado formatado.

```php
format(PriceFormatter::class, 1000); // 1.000,00
```

## Criando uma classe de formatação

Você pode usar o comando `ds:make` para criar uma classe de formatação básica.

```bash
php artisan ds:make formatter CPFFormatter
```

Porém, fornecemos algumas classes de formatação prontas para você usar.

### CPF

```php
//CEP
format(\Devesharp\Support\Formatters\CEPFormatter::class, $CEP);

//CNPJ
format(\Devesharp\Support\Formatters\CNPJFormatter::class, $CNPJ);

//CPF ou CNPJ
format(\Devesharp\Support\Formatters\CPFAndCNPJFormatter::class, $CPFAndCNPJ);

//CPF
format(\Devesharp\Support\Formatters\CPFFormatter::class, $CPF);

// RG
format(\Devesharp\Support\Formatters\RGFormatter::class, $RG);

//Data e hora timezone São Paulo
format(\Devesharp\Support\Formatters\DateTimeBrFormatter::class, $DateTimeBrCarbonOrString);

// Telefone
format(\Devesharp\Support\Formatters\PhoneFormatter::class, $phone);

// Preço
format(\Devesharp\Support\Formatters\PriceFormatter::class, $priceCentavos, $showDecimals);
```