# 🏗 Codebase Overview

This document summarizes the structure and technology stack of the **LCCD Integrated Information System**. Refer to the [project README](../README.md) for a broader description of features and Augustinian values.

## Tech Stack

- **Backend:** PHP 8.2 with [Laravel 12](https://laravel.com/).
- **Frontend:** [Vue 3](https://vuejs.org/), [Vite 6](https://vitejs.dev/), and [Tailwind CSS 4](https://tailwindcss.com/).
- **Testing:** [Pest](https://pestphp.com/) on top of PHPUnit (see `phpunit.xml`).
- **Package Management:** Composer (`composer.json`) and NPM (`package.json`).

## Directory Highlights

- `app/` – Application code (Controllers, Models, Middleware, Providers, and helpers).
- `resources/` – Blade views, Vue components, language files, and styles.
- `config/` – Configuration for services, mail, cache, etc.
- `database/` – Migrations, factories, and seeders.
- `routes/` – Web and API route definitions.
- `tests/` – Feature and unit tests written with Pest.
- `docs/` – In‑depth module documentation and guides.

For a complete diagram of folders, see the [Directory Structure](../README.md#directory-structure-laravel) section of the README.

## Major Modules

The codebase implements several core modules:

- Ticketing
- Job Orders
- Requisition Management
- Inventory
- Purchase Orders
- Document Management
- Document Tracking
- KPI & Audit Dashboard
- User Management and Access Control

Each module has its own documentation within the `docs/` directory.

## Development Tips

1. Install dependencies:
   ```bash
   composer install
   npm install
   ```
2. Copy `.env.example` to `.env` and adjust settings.
3. Run database migrations and seeders if needed:
   ```bash
   php artisan migrate --seed
   ```
4. Start the local environment:
   ```bash
   composer dev
   ```
5. Execute the automated test suite:
   ```bash
   php artisan test
   ```

Use these steps to validate changes before opening a pull request.

