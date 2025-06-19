# 🌳 Environment Setup

This guide documents the main `.env` variables used by the LCCD Integrated Information System. Adjust the values depending on whether you are running **local development** or **production** deployment.

## Base Configuration

Copy `.env.example` to `.env` and update the following keys:

| Key | Description | Dev Example | Production Notes |
|-----|-------------|-------------|-----------------|
| `APP_NAME` | Application name | `Laravel` | Usually the school or system name |
| `APP_ENV` | Environment name | `local` | Use `production` on servers |
| `APP_KEY` | Encryption key | Generated via `php artisan key:generate` | Keep secret |
| `APP_DEBUG` | Debug mode | `true` | Set to `false` in production |
| `APP_URL` | Base URL | `http://localhost:8000` | Public domain |
| `APP_LOCALE` | Default language | `en` | change as needed |
| `APP_FALLBACK_LOCALE` | Fallback language | `en` | |
| `APP_FAKER_LOCALE` | Locale for seeding | `en_US` | |
| `APP_MAINTENANCE_DRIVER` | Maintenance mode driver | `file` | |
| `PHP_CLI_SERVER_WORKERS` | PHP built-in workers | `4` | |
| `BCRYPT_ROUNDS` | Password hashing rounds | `12` | Lower or equal for dev |

> **Important:** Set `APP_URL` to the base address of your application (for example `http://localhost:8000` when using `php artisan serve`). After updating `.env`, run the following command so Laravel reloads the value:

```bash
php artisan config:clear
```

If profile photos or other links fail to load, see [profile-photo-guide.md](profile-photo-guide.md) for troubleshooting tips.

## Logging

| Key | Description |
|-----|-------------|
| `LOG_CHANNEL` | Primary log channel |
| `LOG_STACK` | Channels used in stack |
| `LOG_DEPRECATIONS_CHANNEL` | Where to log deprecations |
| `LOG_LEVEL` | Minimum log level |

## Database

For local development, the default `.env` uses SQLite:

```
DB_CONNECTION=sqlite
```

For production, set MySQL or another driver:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lccd
DB_USERNAME=lccd_user
DB_PASSWORD=secure-password
```

## Sessions & Queues

| Key | Recommended Dev | Production Notes |
|-----|-----------------|-----------------|
| `SESSION_DRIVER` | `database` | Consider `redis` for scalability |
| `SESSION_LIFETIME` | `120` minutes | |
| `SESSION_ENCRYPT` | `false` | Set `true` for sensitive data |
| `QUEUE_CONNECTION` | `database` | Use `redis`/`sqs` in production |

## Caching & Broadcasting

| Key | Description |
|-----|-------------|
| `CACHE_STORE` | Cache driver (default `database`) |
| `BROADCAST_CONNECTION` | Real-time broadcast driver (`log` by default) |

If `CACHE_STORE` is left as `database`, run `php artisan cache:table` so the
cache table exists (you can switch `CACHE_STORE` to `file` instead). Running
`php artisan migrate` afterward will automatically create the table.

## Mail

Update the mail settings for your SMTP server in production.

```
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

For production, configure actual credentials and set `MAIL_MAILER` to `smtp`.

## AWS & Vite

These keys are optional unless you store files in Amazon S3 or use additional Vite settings.

```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```

## Assets

Set `APP_DEFAULT_PROFILE_PHOTO` to change the fallback profile picture path.
The image referenced here **must** exist inside the `public` directory so it can
be served by the web server. By default the application expects
`public/assets/images/default-avatar.png`:

```
APP_DEFAULT_PROFILE_PHOTO=/assets/images/default-avatar.png
```

The application verifies this path on startup. If the image is missing, it logs
a warning and automatically falls back to the bundled avatar above.

## Deployment Tips

1. Keep the `.env` file out of version control.
2. Set `APP_ENV=production` and `APP_DEBUG=false` on your live server.
3. Use secure database and mail credentials.
4. Run `php artisan migrate --seed` after customizing `.env` to create tables and demo data.
5. Run `php artisan config:cache` after updating the environment file for better performance.
6. Verify that `public/storage` exists. If not, run `php artisan storage:link`.

For more details, refer to [codebase_overview.md](codebase_overview.md) and [system-settings.md](system-settings.md).

