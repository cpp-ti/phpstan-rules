# PHPStan Custom Rules

Custom PHPStan rules for code quality enforcement.

## Development Setup

This project uses Docker for development. You don't need PHP or Composer installed locally.

### Prerequisites
- Docker
- Docker Compose

### Quick Start

1. Build the Docker image:
```bash
make build
```

2. Install dependencies:
```bash
make install
```

3. Run tests:
```bash
make test
```

### Available Commands

- `make build` - Build Docker image
- `make install` - Install composer dependencies
- `make test` - Run PHPUnit tests
- `make phpstan` - Run PHPStan on src directory
- `make shell` - Access container shell
- `make help` - Show all available commands

## Installation

```bash
composer require --dev cppti/phpstan-rules
```

## Usage

### Todas as regras

Por padrão, todas as regras são carregadas automaticamente via auto-descoberta do PHPStan. Se preferir ser explícito:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules.neon
```

### Regras específicas

Para usar apenas algumas regras, inclua os arquivos individuais:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/strict-types.neon
    - vendor/cppti/phpstan-rules/rules/disallow-table-name.neon
```

### Arquivos disponíveis

| Arquivo | Descrição |
|---------|-----------|
| `rules.neon` | Todas as regras (compatibilidade) |
| `rules/all.neon` | Todas as regras |
| `rules/strict-types.neon` | Apenas StrictTypesDeclarationRule |
| `rules/disallow-table-name.neon` | Apenas DisallowTableNameInValidationRuleRule |
| `rules/test-namespace.neon` | Apenas TestNamespaceRule |

## Rules

### StrictTypesDeclarationRule

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/strict-types.neon
```

Ensures that all PHP files have `declare(strict_types=1);` at the beginning of the file.

**Example violation:**

```php
<?php

namespace App;

class MyClass
{
    // Missing declare(strict_types=1)
}
```

**Correct:**

```php
<?php

declare(strict_types=1);

namespace App;

class MyClass
{
    // OK
}
```

### DisallowTableNameInValidationRuleRule

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/disallow-table-name.neon
```

Proíbe o uso de nomes de tabelas como strings diretamente em `Rule::unique()` e `Rule::exists()`. Isso força os desenvolvedores a usar `Model::class`, evitando que nomes de tabelas fiquem espalhados pelo código.

**Example violation:**

```php
use Illuminate\Validation\Rule;

$rules = [
    'email' => Rule::unique('users'),
    'category_id' => Rule::exists('categories'),
];
```

**Correct:**

```php
use App\Models\User;
use App\Models\Category;
use Illuminate\Validation\Rule;

$rules = [
    'email' => Rule::unique(User::class),
    'category_id' => Rule::exists(Category::class),
];
```

### TestNamespaceRule

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/test-namespace.neon
```

Garante que classes de teste (arquivos terminados em `Test.php`) tenham um namespace começando com `Tests\`. Isso mantém a organização e padronização dos testes no projeto.

**Example violation:**

```php
<?php

declare(strict_types=1);

namespace App\Tests; // Errado: não começa com "Tests\"

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
}
```

**Correct:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit; // Correto: começa com "Tests\"

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
}
```

## Disallowed Calls

Este pacote inclui o [spaze/phpstan-disallowed-calls](https://github.com/spaze/phpstan-disallowed-calls), que permite proibir o uso de funções, métodos, constantes e namespaces específicos.

As regras de quais chamadas são proibidas devem ser configuradas no projeto que utiliza este pacote. Adicione no seu `phpstan.neon`:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules.neon

parameters:
    disallowedFunctionCalls:
        -
            function: 'dd()'
            message: 'Use logger ao invés de dd()'
        -
            function: 'dump()'
            message: 'Remova dump() antes do commit'
        -
            function: 'var_dump()'
        -
            function: 'print_r()'
        -
            function: 'exit()'
        -
            function: 'die()'

    disallowedMethodCalls:
        -
            method: 'Illuminate\Support\Facades\Log::debug()'
            message: 'Não use Log::debug() em produção'

    disallowedStaticCalls:
        -
            method: 'SomeClass::deprecatedMethod()'
            message: 'Método depreciado, use newMethod()'
```

### Tipos de restrições disponíveis

| Parâmetro | Descrição |
|-----------|-----------|
| `disallowedFunctionCalls` | Funções globais (ex: `dd()`, `var_dump()`) |
| `disallowedMethodCalls` | Métodos de instância |
| `disallowedStaticCalls` | Chamadas estáticas |
| `disallowedConstants` | Constantes |
| `disallowedNamespaces` | Namespaces inteiros |
| `disallowedSuperglobals` | Superglobais (ex: `$_GET`, `$_POST`) |

Para mais opções e configurações avançadas, consulte a [documentação do spaze/phpstan-disallowed-calls](https://github.com/spaze/phpstan-disallowed-calls).
