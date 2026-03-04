# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Custom PHPStan 2.0+ rules package (`cppti/phpstan-rules`). PHP 8.2+ required.

## Commands

All commands run via Docker:

```bash
make test              # Run PHPUnit tests
make phpstan           # Run PHPStan on src (level=max)
make phpstan-test      # Run PHPStan on tests (level=max)
make install           # Install composer dependencies
```

Run a single test:
```bash
docker-compose run --rm php vendor/bin/phpunit --filter=TestClassName
docker-compose run --rm php vendor/bin/phpunit tests/Rules/StrictTypesDeclarationRuleTest.php
```

## Architecture

### Rules

Each rule lives in `src/Rules/` and implements `PHPStan\Rules\Rule`. A rule must define:
- `getNodeType()` — the AST node class to inspect
- `processNode()` — validation logic returning `RuleError[]`

Each rule has a corresponding `.neon` file in `rules/` that registers it as a `phpstan.rules.rule` tagged service. New rules must be added to `rules/all.neon`.

The root `rules.neon` includes `rules/all.neon` for auto-discovery via composer's `extra.phpstan.includes`.

### Tests

Tests extend `PHPStan\Testing\RuleTestCase` and live in `tests/Rules/`. Each test:
- Overrides `getRule()` to return the rule instance
- Uses `$this->analyse()` with fixture files and expected `[message, line]` pairs

Test fixture files (PHP files that trigger or pass the rules) go in `tests/Rules/data/`.

### Adding a New Rule

1. Create `src/Rules/MyRule.php` implementing `PHPStan\Rules\Rule`
2. Create `rules/my-rule.neon` registering the service with `phpstan.rules.rule` tag
3. Add the include to `rules/all.neon`
4. Create `tests/Rules/MyRuleTest.php` extending `RuleTestCase`
5. Add fixture files in `tests/Rules/data/`

### Disallowed Calls

`rules/disallowed.neon` uses `spaze/phpstan-disallowed-calls` to ban specific functions/methods. Consumer projects can extend this via their own PHPStan config.
