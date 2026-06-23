# Vision App

Vision App is a Laravel-based product for authenticated image scanning and product enrichment. The application accepts uploaded images, sends them through Google Vision for OCR and image analysis, stores the scan lifecycle in the database, and exposes the results through an API and a Backpack admin area.

The project is built for a modern Laravel stack with queued background processing, Redis-backed workers, Sanctum authentication, and a MySQL database.

## What It Does

- Accepts authenticated image uploads through the API.
- Queues OCR and image-analysis work so scans do not block the request cycle.
- Uses Google Vision to extract text, labels, and dominant colors from images.
- Persists scan requests, scan results, and derived user products.
- Provides an admin entry point at `/admin` through Backpack.
- Exposes Laravel Sanctum-protected API endpoints for client applications.

## Technology Stack

- Laravel 13
- PHP 8.3
- MySQL 8
- Redis for queue and cache support
- Laravel Sanctum for API authentication
- Laravel Horizon for queue monitoring
- Backpack CRUD and Permission Manager for admin tooling
- Google Vision API for image analysis
- Vite and Tailwind CSS for frontend assets

## Repository Layout

- `app/Http/Controllers` contains the API and admin-facing controllers.
- `app/Jobs` contains queued background jobs such as image OCR processing.
- `app/Models` contains the data models for scan requests, scan results, and user products.
- `app/Services` contains external service integrations, including Google Vision.
- `database/migrations` defines the schema for scans, products, jobs, and permissions.
- `routes/api.php` defines the authenticated API surface.
- `routes/web.php` redirects the landing page to the admin area.

## Requirements

- PHP 8.3 or later
- Composer
- Node.js 20 or later
- MySQL 8
- Redis
- A Google Vision API key

## Local Setup

### Option 1: Docker

The repository includes a `docker-compose.yml` with services for the application, Nginx, MySQL, and Redis.

1. Start the stack:

```bash
docker compose up -d --build
```

2. Install PHP dependencies inside the app container or from your host if your environment is already configured.

3. Copy and configure the environment file:

```bash
cp .env.example .env
php artisan key:generate
```

4. Run migrations and storage setup:

```bash
php artisan migrate
php artisan storage:link
```

5. Install frontend dependencies and build assets:

```bash
npm install
npm run dev
```

### Option 2: Local PHP Environment

1. Install dependencies:

```bash
composer install
npm install
```

2. Prepare the environment:

```bash
cp .env.example .env
php artisan key:generate
```

3. Configure the database, Redis, and Google Vision credentials in `.env`.

4. Run migrations:

```bash
php artisan migrate
php artisan storage:link
```

5. Build and serve the frontend:

```bash
npm run dev
```

## Environment Variables

The following values are required or commonly used by the application:

```env
APP_NAME="Vision App"
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=vision_app
DB_USERNAME=root
DB_PASSWORD=

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

GOOGLE_VISION_API_KEY=your-google-vision-key
```

If you use Sanctum from a browser-based client, configure the usual session and stateful-domain settings for your local and production domains.

## Running The Application

### Web application

- Open the application root in a browser.
- The default route redirects to `/admin`.
- Use the Backpack admin area to manage records and inspect application data.

### API

The API is mounted under `/api`.

Public endpoints:

- `POST /api/register`
- `POST /api/login`
- `GET /api/ping`

Authenticated endpoints protected by `auth:sanctum`:

- `POST /api/logout`
- `POST /api/upload`
- `POST /api/scan`
- `GET /api/result/{id}`

## Scan Workflow

1. A client uploads an image through the API.
2. The application creates a `scan_requests` record.
3. A queued job processes the image through Google Vision.
4. OCR and image-analysis data are stored in `scan_results`.
5. The scan request status transitions through the processing lifecycle and is eventually marked `done` or `failed`.
6. Derived product data can be saved to `user_products` for later use.

## Background Processing

This application relies on queue workers for image processing. In development, the Composer `dev` script starts the application server, queue listener, log viewer, and Vite in one command.

```bash
composer dev
```

If you prefer to run the pieces individually:

```bash
php artisan serve
php artisan queue:listen --tries=1 --timeout=0
php artisan pail --timeout=0
npm run dev
```

Horizon is available for queue monitoring when configured in your environment.

## Useful Commands

```bash
php artisan migrate
php artisan migrate:fresh --seed
php artisan test
php artisan queue:work
php artisan horizon
php artisan storage:link
```

## Configuration Notes

- Google Vision credentials are read from `GOOGLE_VISION_API_KEY`.
- The application uses Redis for queued processing and cache-backed services.
- Public uploads should be stored in `storage/app/public` and linked into `public/storage`.
- If you change the database schema, update the relevant models and admin CRUD configuration together so the admin UI stays aligned with the data model.

## Production Checklist

- Set `APP_ENV=production` and `APP_DEBUG=false`.
- Configure the database, Redis, and Google Vision secrets.
- Run migrations before releasing.
- Build frontend assets with `npm run build`.
- Ensure a queue worker or Horizon process is running.
- Verify the storage symlink is present.

## License

This project is open-sourced software released under the MIT license.
