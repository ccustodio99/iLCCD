# ⚙️ Admin Settings – LCCD Integrated Information System

Administrators can manage baseline values used across the modules from the **Settings** section of the sidebar. Each record includes an `is_active` flag so items may be disabled without deletion. The Settings link is visible only for users with the **admin** role.

## Default Records
- **Ticket Categories:** Computers & Devices, Network & Access, Facilities & Maintenance, Procurement & Inventory, Academics & Systems, Security & Safety, Support & Training, Feedback & Improvement, Other / General Inquiry (each category includes its own subcategories)
- **Job Order Types:** Installation & Deployment, Maintenance, Inspection & Audit, Emergency Response, Upgrades & Updates, Calibration & Testing, Decommissioning & Removal, Cleaning & Housekeeping, Other Job Request
- **Inventory Categories:** Electronics, Supplies, Furniture
- **Document Categories:** Policies & Procedures, Forms & Templates, Course Materials, Student Records, Financial & Accounting, Research & Publications, Marketing & Communications, Meeting Minutes & Reports, Archives & Historical, Miscellaneous

These defaults come from **`Database\Seeders\DocumentCategorySeeder`** and are created when running `php artisan migrate --seed`. Demo data from **`DemoSeeder`** reuses these records so no duplicates are created. You can modify or expand them later using the settings screens.

Additional tools include **Theme Settings** where administrators can pick primary and accent colors, select fonts, and edit the home page text. The new **Announcements** manager lets admins post messages that appear on the dashboard.
