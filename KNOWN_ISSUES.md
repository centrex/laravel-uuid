# Known Issues — laravel-uuid

_Last checked: 2026-08-02_

## Failing tests

The entire Pest suite is unable to run — it dies with a PHP fatal error before any test executes:

```
PHP Fatal error:  Constant expression contains invalid operations in /home/octopus/lab/laravel_plugins/laravel-uuid/src/HasUuid.php on line 13
```

Cause: `src/HasUuid.php:13` declares a property default using a function call:

```php
protected $uuid_column = config('laravel-uuid.default_uuid_column');
```

PHP class property defaults must be constant expressions — calling `config()` here is not allowed, so **any file that loads this trait (including PHPUnit/Pest's arch-test class scanner, and any real application using the package) fatals immediately.** This is not a test-only artifact; it would break in production the same way the moment `HasUuid` is autoloaded/reflected. `getUuidColumnName()` (line 33-36) already has a working fallback pattern (`$this->uuid_column ?? 'uuid'`) that implies the intent was to lazily resolve the config value at runtime, not at property-declaration time — the property default should presumably just be `null` (or the property removed) and resolved inside `getUuidColumnName()`.

Because of this fatal error, `composer test:unit` / `vendor/bin/pest` never produces a pass/fail report — the whole suite is blocked.

## Style / static-analysis debt

- `vendor/bin/rector --dry-run` reports **2 files** with pending refactors: `src/LaravelUuidServiceProvider.php`, `tests/TestCase.php` (all `AddOverrideAttributeToOverriddenMethodsRector`). Run `composer refacto` to apply.
- `vendor/bin/pint --test` — clean, no style violations.
- PHPStan (`level: max`) — clean, **0 errors** (phpstan-baseline.neon is empty and not needed). Note phpstan itself did not catch the fatal constant-expression bug above; that's a runtime/parse-time error PHPStan's static analysis pass didn't flag.

## TODO / FIXME markers

None found (`grep -rn "TODO\|FIXME" --include="*.php" src/ config/ database/` — no matches).

## Open GitHub issues

Not checked — the `gh` CLI is not installed in this environment.
