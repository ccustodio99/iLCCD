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
