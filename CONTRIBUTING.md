# Contributing

Thanks for considering contributing to **codetech/laravel-api-logs**!

## Which branch?

- **New features and improvements**: target the [`main`](https://github.com/CodeTechAgency/laravel-api-logs/tree/main) branch (3.x, Laravel 11–13).
- **Security fixes for the 2.x line**: target the [`v2`](https://github.com/CodeTechAgency/laravel-api-logs/tree/v2) branch. No other changes are accepted there.
- The [`v1`](https://github.com/CodeTechAgency/laravel-api-logs/tree/v1) branch is end-of-life and receives no changes.

## Getting started

```bash
git clone git@github.com:CodeTechAgency/laravel-api-logs.git
cd laravel-api-logs
composer install
```

## Before submitting a pull request

Run the full quality suite locally — CI runs the same checks:

```bash
composer test      # PHPUnit test suite (Unit + Feature)
composer lint      # Pint code-style check (run `composer format` to fix)
composer analyse   # PHPStan static analysis
```

- Add tests for any change in behaviour. Unit tests live in `tests/Unit`, feature tests in `tests/Feature`.
- Keep pull requests focused: one feature or fix per PR.
- Use a [conventional-commit](https://www.conventionalcommits.org) style title, e.g. `fix(middleware): handle missing causer`.
- Reference the related issue in the PR description. If there is no issue yet, please open one first so the change can be discussed.

## Reporting bugs

Open an issue using the bug report template and include the package, Laravel and PHP versions plus the smallest reproduction you can manage.

**Security vulnerabilities must not be reported publicly** — see the [security policy](https://github.com/CodeTechAgency/laravel-api-logs/blob/main/SECURITY.md).
