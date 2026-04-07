# UUID trait for Laravel Eloquent models

[![Latest Version on Packagist](https://img.shields.io/packagist/v/centrex/laravel-uuid.svg?style=flat-square)](https://packagist.org/packages/centrex/laravel-uuid)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-uuid/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/centrex/laravel-uuid/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/centrex/laravel-uuid/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/centrex/laravel-uuid/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/centrex/laravel-uuid?style=flat-square)](https://packagist.org/packages/centrex/laravel-uuid)

Auto-generates a UUID v4 on model creation, sets it as the route key, and provides a query scope for UUID lookups.

## Installation

```bash
composer require centrex/laravel-uuid
```

## Usage

### 1. Add the column to your migration

```php
$table->uuid('uuid')->unique();
```

### 2. Add the trait to your model

```php
use Centrex\LaravelUuid\HasUuid;

class Order extends Model
{
    use HasUuid;
}
```

A UUID is automatically generated on `creating` if the `uuid` column is present and empty.

### 3. Route model binding

The trait overrides `getRouteKeyName()` to return `'uuid'`, so route model binding works out of the box:

```php
// routes/web.php
Route::get('/orders/{order}', [OrderController::class, 'show']);
// URL: /orders/550e8400-e29b-41d4-a716-446655440000
```

### 4. Query scope

```php
Order::uuid('550e8400-e29b-41d4-a716-446655440000')->first();
```

### Custom column name

Override `$uuid_column` on the model or publish the config:

```php
class Order extends Model
{
    use HasUuid;

    protected $uuid_column = 'public_id';
}
```

```bash
php artisan vendor:publish --tag="laravel-uuid-config"
```

## Testing

```bash
composer test        # full suite
composer test:unit   # pest only
composer test:types  # phpstan
composer lint        # pint
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [centrex](https://github.com/centrex)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
