# ⚙️ Admin Settings – LCCD Integrated Information System

Administrators can manage baseline values used across the modules from the **Settings** section of the sidebar. Each record includes an `is_active` flag so items may be disabled without deletion.

## Default Records
- **Ticket Categories:** IT, Facilities, Documents
- **Job Order Types:** Repair, Installation, Setup
- **Inventory Categories:** Electronics, Supplies, Furniture
- **Document Categories:** Policy, Syllabus, Report

These defaults are seeded by `php artisan migrate --seed` and can be modified or expanded using the settings screens.
