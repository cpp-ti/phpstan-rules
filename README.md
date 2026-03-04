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

### All rules

By default, all rules are loaded automatically via PHPStan's auto-discovery. If you prefer to be explicit:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules.neon
```

### Specific rules

To use only some rules, include the individual files:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/strict-types.neon
    - vendor/cppti/phpstan-rules/rules/disallow-table-name.neon
    - vendor/cppti/phpstan-rules/rules/disallow-env-usage.neon
```

### Available files

| File | Description |
|------|-------------|
| `rules.neon` | All rules (compatibility) |
| `rules/all.neon` | All rules |
| `rules/strict-types.neon` | Only StrictTypesDeclarationRule |
| `rules/disallow-table-name.neon` | Only DisallowTableNameInValidationRuleRule |
| `rules/test-namespace.neon` | Only TestNamespaceRule |
| `rules/disallow-env-usage.neon` | Only DisallowEnvUsageRule |
| `rules/disallowed.neon` | Only disallowed calls rules (spaze/phpstan-disallowed-calls) |

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

Disallows the use of table names as strings directly in `Rule::unique()` and `Rule::exists()`. This forces developers to use `Model::class`, preventing table names from being scattered throughout the code.

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

### DisallowEnvUsageRule

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/disallow-env-usage.neon
```

Disallows the use of `env()` outside of allowed paths. In production with `php artisan config:cache`, calls to `env()` outside of `config/` return `null`, causing silent bugs.

By default, `env()` is only allowed inside files whose path contains `config/`. You can customize the allowed paths:

```neon
services:
    -
        class: Cppti\PHPStanRules\Rules\DisallowEnvUsageRule
        arguments:
            allowedPaths:
                - config/
                - bootstrap/
        tags:
            - phpstan.rules.rule
```

**Example violation:**

```php
// app/Services/PaymentService.php
$apiKey = env('PAYMENT_API_KEY');
```

**Correct:**

```php
// app/Services/PaymentService.php
$apiKey = config('payment.api_key');
```

### TestNamespaceRule

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/test-namespace.neon
```

Ensures that test classes (files ending in `Test.php`) have a namespace starting with `Tests\`. This maintains organization and standardization of tests in the project.

**Example violation:**

```php
<?php

declare(strict_types=1);

namespace App\Tests; // Wrong: does not start with "Tests\"

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
}
```

**Correct:**

```php
<?php

declare(strict_types=1);

namespace Tests\Unit; // Correct: starts with "Tests\"

use PHPUnit\Framework\TestCase;

class ExampleTest extends TestCase
{
}
```

## Disallowed Calls

This package includes [spaze/phpstan-disallowed-calls](https://github.com/spaze/phpstan-disallowed-calls), which allows you to disallow the use of specific functions, methods, constants, and namespaces.

To use only this functionality without the other rules:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules/disallowed.neon
```

The rules for which calls are disallowed must be configured in the project using this package. Add to your `phpstan.neon`:

```neon
includes:
    - vendor/cppti/phpstan-rules/rules.neon

parameters:
    disallowedFunctionCalls:
        -
            function: 'dd()'
            message: 'Use logger instead of dd()'
        -
            function: 'dump()'
            message: 'Remove dump() before committing'
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
            message: 'Do not use Log::debug() in production'

    disallowedStaticCalls:
        -
            method: 'SomeClass::deprecatedMethod()'
            message: 'Deprecated method, use newMethod()'
```

### Available restriction types

| Parameter | Description |
|-----------|-------------|
| `disallowedFunctionCalls` | Global functions (e.g. `dd()`, `var_dump()`) |
| `disallowedMethodCalls` | Instance methods |
| `disallowedStaticCalls` | Static calls |
| `disallowedConstants` | Constants |
| `disallowedNamespaces` | Entire namespaces |
| `disallowedSuperglobals` | Superglobals (e.g. `$_GET`, `$_POST`) |

For more options and advanced configuration, see the [spaze/phpstan-disallowed-calls documentation](https://github.com/spaze/phpstan-disallowed-calls).
