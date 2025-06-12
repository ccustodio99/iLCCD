# ⚙️ Admin Settings – LCCD Integrated Information System

Administrators can manage baseline values used across the modules from the **Settings** section of the sidebar. Each record includes an `is_active` flag so items may be disabled without deletion. The Settings link is visible only for users with the **admin** role.

## Default Records
- **Ticket Categories:** IT, Facilities, Documents, Supplies, Finance, HR, Registrar, Clinic, Security (each category includes its own subcategories, e.g., IT → Hardware/Software)
- **Job Order Types:** Repair, Installation, Setup
- **Inventory Categories:** Electronics, Supplies, Furniture
- **Document Categories:** Policy, Syllabus, Report

These defaults are seeded by `php artisan migrate --seed` and can be modified or expanded using the settings screens.

Additional tools include **Theme Settings** where administrators can pick primary and accent colors, select fonts, and edit the home page text. The new **Announcements** manager lets admins post messages that appear on the dashboard.
