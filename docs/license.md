# Licensing Workflow

The system requires a valid license record to operate. Licenses are created using the `license:generate` Artisan command which outputs a signed key. The key encodes the license ID and expiry timestamp and is protected using an HMAC signature.

The signing secret is read from the `LICENSE_SECRET` environment variable. If this variable is not set, Laravel's `APP_KEY` will be used instead. Set `LICENSE_SECRET` to the same value on every server so that generated licenses can be validated elsewhere.

For a fresh installation, run the command without flags to create the initial license:

```bash
php artisan license:generate
```

The command prints the full path to the new license file followed by the encoded string. If you generated that string on another server, open `/license` on the target instance and submit it to activate. The `/license` route is reachable even without a license because the `CheckLicense` middleware excludes it from verification.

1. **Generate a license**
   ```bash
   php artisan license:generate --days=30
   ```
   The command stores the license in the database and prints both the file path and encoded string, which can be used for activation.
2. **Activate or renew**
   Submit the encoded string through the activation or renewal form.  The application verifies the signature and expiry before enabling the license.
3. **Runtime checks**
   Every request passes through the `CheckLicense` middleware.  If the current license is missing or expired, access is denied.
4. **Scheduled validation**
   A daily task in `app/Console/Kernel.php` disables the system once the license expires.

### Cache Invalidation After Migrations

The helper that checks for the `licenses` table caches the result using Laravel's
`once()` utility. If your migrations create or drop the table while the
application is running, clear this cache so subsequent requests detect the change:

```php
// inside a migration's up() or down()
license_table_cache_clear();
```

Running `php artisan optimize:clear` after migrations has the same effect.

For commercial deployments, ensure a valid license is generated and kept up to date.

## Removing a License

If you need to deactivate the system, navigate to `/license` while logged in. When an active license exists, a **Remove License** button appears below the activation form. Submitting this form deletes the license record, immediately disabling all protected routes until a new license is activated.
