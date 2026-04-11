# CLAUDE.md

## Package Overview

`centrex/laravel-uuid` — UUID trait for Eloquent models, auto-generating UUIDs on model creation.

Namespace: `Centrex\LaravelUuid\`  
Service Provider: `LaravelUuidServiceProvider`  
Trait: `HasUuid`

## Commands

Run from inside this directory (`cd laravel-uuid`):

```sh
composer install          # install dependencies
composer test             # full suite: rector dry-run, pint check, phpstan, pest
composer test:unit        # pest tests only
composer test:lint        # pint style check (read-only)
composer test:types       # phpstan static analysis
composer test:refacto     # rector refactor check (read-only)
composer lint             # apply pint formatting
composer refacto          # apply rector refactors
composer analyse          # phpstan (alias)
composer build            # prepare testbench workbench
composer start            # build + serve testbench dev server
```

Run a single test:
```sh
vendor/bin/pest tests/ExampleTest.php
vendor/bin/pest --filter "test name"
```

## Structure

```
src/
  HasUuid.php                   # Trait — add to any model
  LaravelUuidServiceProvider.php
config/config.php
database/migrations/
tests/
workbench/
```

## Usage

```php
use Centrex\LaravelUuid\HasUuid;

class Product extends Model
{
    use HasUuid;
}

// UUID is auto-generated on create; no manual assignment needed
$product = Product::create(['name' => 'Widget']);
echo $product->uuid; // e.g. "550e8400-e29b-41d4-a716-446655440000"
```

## Conventions

- PHP 8.2+, `declare(strict_types=1)` in all files
- Pest for tests, snake_case test names
- Pint with `laravel` preset
- Rector targeting PHP 8.3 with `CODE_QUALITY`, `DEAD_CODE`, `EARLY_RETURN`, `TYPE_DECLARATION`, `PRIVATIZATION` sets
- PHPStan at level `max` with Larastan
