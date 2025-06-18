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
