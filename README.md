# Minex QuickAdmin

Minex QuickAdmin is a Laravel-based administration panel and CMS for managing applications, categories, products,
brands, and other content for the Minex project.

## Overview

This project provides a robust backend for managing a multi-lingual catalog of industrial equipment and services. It
includes features for SEO management, media handling, and role-based access control.

- **Framework:** [Laravel 10.x](https://laravel.com/docs/10.x)
- **Language:** PHP 8.1+
- **Frontend:** Vite, Blade, Tailwind CSS / Custom CSS
- **Database:** MySQL (recommended)

## Requirements

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL or PostgreSQL
- GD Extension or ImageMagick (for image processing)

## Setup

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd minex-quickadmin
   ```

2. **Install PHP dependencies:**
   ```bash
   composer install
   ```

3. **Install JS dependencies:**
   ```bash
   npm install
   ```

4. **Environment Configuration:**
   Copy the example environment file and configure your database and other settings.
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database Setup:**
   Create a database and update `.env` with your credentials. Then run migrations and seeders (if available).
   ```bash
   php artisan migrate --seed
   ```

6. **Link Storage:**
   ```bash
   php artisan storage:link
   ```

7. **Build Assets:**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

## Run Commands

- **Local Development Server:**
  ```bash
  php artisan serve
  ```
- **Frontend Development (Vite):**
  ```bash
  npm run dev
  ```
- **Queue Worker (if used):**
  ```bash
  php artisan queue:work
  ```

## Scripts

### Composer Scripts

- `post-autoload-dump`: Discovers packages and clears cache.
- `post-update-cmd`: Publishes Laravel assets.

### NPM Scripts (defined in `package.json`)

- `dev`: Starts the Vite development server.
- `build`: Builds assets for production.

## Environment Variables

Key variables in `.env`:

- `APP_NAME`: Name of the application (default: Laravel).
- `APP_ENV`: Application environment (`local`, `production`).
- `APP_KEY`: Application encryption key.
- `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`: Database connection details.
- `MAIL_*`: Mail server configuration.

## Tests

The project uses PHPUnit for testing.

- **Run all tests:**
  ```bash
  php artisan test
  ```
  or
  ```bash
  ./vendor/bin/phpunit
  ```

*Note: The project contains default example tests. TODO: Add comprehensive tests for custom modules.*

## Project Structure

- `app/Models/`: Eloquent models (Application, Product, Category, etc.).
- `app/Http/Controllers/`: Request handling logic.
- `database/migrations/`: Database schema definitions.
- `resources/views/`: Blade templates.
- `routes/web.php`: Web routes and entry points.
- `public/`: Publicly accessible files and entry point `index.php`.

## TODO

- [ ] Implement full unit and feature tests for business logic.
- [ ] Document custom helper functions in `app/Helpers`.
- [ ] Add specific deployment notes.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
