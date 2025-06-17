# 🧪 Testing Guide

This guide explains how to test the **LCCD Integrated Information System** before opening a pull request.

## Automated Tests

- The project uses [Pest](https://pestphp.com/) on top of PHPUnit.
- All test files live in the `tests/` directory.
- Run the full suite with:

```bash
php artisan test
```

- Use the `--testsuite=Unit` or `--testsuite=Feature` option to target specific suites.
- Add new tests for every feature and cover common failure cases.

## Manual Testing

1. Log in as various user roles (administrator, staff, finance, etc.).
2. Exercise each module—tickets, job orders, requisitions, inventory, purchase orders, and documents.
3. Confirm both normal and erroneous flows work as expected.

Running these checks helps keep the codebase reliable and upholds our commitment to quality.
