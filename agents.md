# agents.md

## Agent Guidance — laravel-uuid

### Package Purpose
Adds a `HasUuid` trait to Eloquent models that automatically generates a UUID (v4 by default) on model creation and stores it in a `uuid` column.

### Before Making Changes
- Read `src/HasUuid.php` — the entire package is this one trait
- Read `src/LaravelUuidServiceProvider.php` — minimal, just registers the package
- Check `database/migrations/` — the migration adds the `uuid` column

### Common Tasks

**Switching UUID version (v4 → v7)**
- UUID v7 is time-ordered and better for database indexing
- Change the generation call in `HasUuid.php` from `Str::uuid()` (v4) to `Str::orderedUuid()` (v7, Laravel 9+) or use the `ramsey/uuid` library directly
- Make the version configurable via `config/config.php` so host apps can choose
- Existing stored UUIDs are not affected — only new records use the new version

**Using UUID as the primary key**
- Some host apps want `uuid` as the `$primaryKey` instead of `id`
- This is a separate concern — add a `HasUuidPrimaryKey` trait variant rather than changing the existing trait
- Do not force UUID as PK by default — many apps use integer PKs with a UUID as a public identifier

**Making UUID unique index**
- The migration should add `->unique()` to the `uuid` column
- Check existing migration before adding — do not duplicate the index

**Exposing UUID in API responses**
- This package only stores UUIDs — hiding integer PKs in API responses is the host app's responsibility
- Do not add API resource classes here

### Testing
```sh
composer test:unit        # pest
composer test:types       # phpstan
composer test:lint        # pint
```

Test that UUID is auto-generated and unique:
```php
$model1 = TestModel::create();
$model2 = TestModel::create();
expect($model1->uuid)->not->toBeNull();
expect($model1->uuid)->not->toBe($model2->uuid);
```

### Safe Operations
- Adding a `HasUuidPrimaryKey` trait variant
- Making UUID version configurable
- Adding tests

### Risky Operations — Confirm Before Doing
- Changing the UUID column name from `uuid` to something else (breaks existing host app queries)
- Changing UUID version for existing installs (existing records keep old format — mixed formats in one table)

### Do Not
- Auto-assign UUID on `updating` events — UUIDs are immutable once set
- Expose a `regenerateUuid()` method — this would break any external references
- Skip `declare(strict_types=1)` in any new file
