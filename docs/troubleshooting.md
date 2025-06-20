# Troubleshooting

This guide collects common issues and fixes when running the LCCD Integrated Information System locally.

## Approval stages missing after editing a process
If you edit an Approval Process and no stages appear (the table shows just `-- None --`), the database tables may not be seeded. Run:

```bash
php artisan migrate --seed
```

This creates the `approval_processes` and `approval_stages` tables with sample data. You can also reseed just the workflows:

```bash
php artisan db:seed --class=ApprovalProcessSeeder
```

After seeding, refresh the **Settings → Approval Processes** page and the stages should be visible.

## "View [settings.partials.settings-modal] not found" after pulling changes
If you encounter this error, the compiled Blade views under `storage/framework/views` may still reference a template that was deleted in commit `f885197f`. Clear the cached views so Laravel regenerates them:

```bash
php artisan view:clear
```

You can also run the broader cache clear command if other caches cause issues:

```bash
php artisan optimize:clear
```

Reload the page after running these commands and the error should disappear.

## Common Settings Issues

### Stale compiled views
If a settings page fails with a missing Blade view error after pulling changes, clear the compiled templates:

```bash
php artisan view:clear
```

### Timezone change not applied immediately
`updateLocalization()` now calls `date_default_timezone_set()` so the timezone is updated for **the current request only**. Refresh after saving to verify the timezone and restart any queue workers or scheduled tasks so they pick up the new value.

### Brand image cleanup fails
Old images sometimes remained when paths lacked the `storage/` prefix. Paths are normalized on deletion so files are removed correctly.

### Cache keeps outdated settings
`Setting::get()` previously cached missing keys forever. It now only caches existing records. Run `php artisan cache:clear` if you add settings manually.

### Numeric values returned as strings
The `Setting` model automatically casts integer strings to integers. Clear the cache if you still receive a string for values like `sla_interval`.

### Unsanitized notification templates
Notification templates are stored in Markdown format and converted to HTML when messages are generated. This ensures the raw Markdown is never rendered directly.
