# System Settings

Administrators manage system defaults and theme settings here from the **Settings** link in the profile dropdown. Each record includes an `is_active` flag so items may be disabled without deletion. The Settings link is visible only for users with the **admin** role.

## Interface Layout

The landing page organizes options into an accordion with four sections:

1. **Categories** – Ticket Categories, Job Order Types, Inventory Categories and Document Categories.
2. **General** – Appearance, Localization and Ticket Escalation.
3. **Workflow** – Approval Processes.
4. **Communication** – Announcements, Notifications, Email and Contact Info.

Icons and concise labels help users quickly find what they need, and every card includes an accessible name. Breadcrumbs keep the current context visible and all forms provide a **Cancel** button. These patterns align with the project's [UX Design Principles](user-interface-branding.md#ux-design-principles).

## Default Records
The seeders populate several baseline records used throughout the system.

- **Ticket Categories:** Computers & Devices, Software & Apps, Network & Access, User Accounts & Access, Printing & Scanning, Procurement & Inventory, Facilities & Maintenance, Security & Safety, Training & Support, Feedback & Improvement, Other / General Inquiry (each parent has its own subcategories; see [Ticket Categories](ticket-categories.md))
- **Job Order Types:** Installation & Deployment, IT Equipment Setup (matches Computers & Devices), Software Deployment (matches Software & Apps), Classroom AV Installation (matches Classroom AV), Maintenance, Preventative Maintenance (links Facilities & Maintenance), Corrective Repairs (Doors, Plumbing, HVAC), Inspection & Audit, Safety & Compliance Audits (e.g. Fire Extinguisher Tests), Inventory Spot-Checks (cross-links Inventory Management), Emergency Response, Power Outages (matches Electrical Outages), Critical Network/Server Downtime (matches Network Outages), Upgrades & Updates, Hardware Upgrades (RAM, Storage), Software Patching & Version Updates, Calibration & Testing, Lab Equipment Calibration (links Laboratory Equipment), Printer/Scanner Accuracy Checks (cross-links Printing & Scanning), Decommissioning & Removal, Cleaning & Housekeeping, Other Job Request
- **Inventory Categories:**
  - **Electronics**
    - Computers & Laptops
    - Tablets & Chromebooks
    - Smartphones & Mobile Devices
    - Networking Gear (Routers, Switches)
    - AV Equipment (Projectors, Microphones)
  - **Furniture & Fixtures**
    - Desks & Chairs
    - Cabinets & Shelving
    - Laboratory Benches
    - Classroom Fixtures (Podiums, Whiteboards)
  - **Office Supplies**
    - Paper & Stationery
    - Printing Consumables (Toner, Ink)
    - Desk Accessories (Pens, Clips)
  - **Laboratory Equipment**
    - Instruments & Sensors
    - Calibration Tools (links Calibration & Testing)
    - Safety Gear (Goggles, Gloves)
  - **Educational Materials**
    - Textbooks & Reference Books
    - AV Media (DVDs, Slides)
    - Teaching Aids (Models, Charts)
  - **Maintenance & Cleaning**
    - HVAC & Electrical Parts
    - Plumbing Supplies
    - Cleaning Chemicals & Tools
  - **Safety & First Aid**
    - Fire Extinguishers (cross-links Safety Audits)
    - First-Aid Kits
    - Emergency Signage
  - **Vehicles & Grounds**
    - Campus Vehicles
    - Grounds Equipment (Mowers, Trimmers)
    - Outdoor Furniture
  - **Consumables & Perishables**
    - Lab Reagents & Chemicals
    - Printer Paper Rolls
    - Batteries & Bulbs
  - **Miscellaneous**

  See the [Inventory Module](inventory-module.md#managing-inventory-categories)
  guide for steps on creating parent categories and subcategories in the
  settings UI.
- **Document Categories:** Policies & Procedures, Forms & Templates, Course Materials, Student Records, Financial & Accounting, Research & Publications, Marketing & Communications, Meeting Minutes & Reports, Archives & Historical, Miscellaneous

Ticket categories already use a parent → child hierarchy with a `parent_id` field. Additional category types may adopt similar relationships in future versions.

These defaults come from **`Database\Seeders\DocumentCategorySeeder`** and are created when running `php artisan migrate --seed`. Demo data from **`DemoSeeder`** reuses these records so no duplicates are created. You can modify or expand them later using the settings screens.

The new **Announcements** manager lets admins post messages that appear on the dashboard.

## Theme, Branding & Institution
All appearance settings are grouped together so administrators can update the color scheme, fonts, logos, and institution text in one place.

### Theme Options
- **Primary Color** – main navigation and button color
- **Accent Color** – highlight shade for links and callouts
- **Primary Font** – used for headings
- **Secondary Font** – used for body text
- **Home Page Heading** – large text shown on the landing page
- **Home Page Tagline** – short tagline displayed below the heading

### Institution Text
- **Header** – small line shown near the top of each page. Use `\n` for a new line.
- **Footer** – message displayed above the copyright notice. Use `\n` for a new line. The token `{year}` is replaced with the current year when displayed.
- **Show Footer** – toggle visibility of the footer across the site.

### Brand Images
- **Logo** – appears in the navigation menu header
- **Favicon** – used in the browser tab and bookmarks
- **Profile Photo Fallback** – default image for accounts without a custom photo

Open **Settings → Appearance** in the navigation menu to modify these values. Color pickers let administrators select the Primary and Accent shades, dropdown menus list available font families, simple text boxes set the Home Page Heading and Tagline, and file upload fields manage logos and favicons. *A screenshot of the Appearance Settings form is available in the ITRC Dropbox.*

### Theme Setting Defaults
The initial seed runs **`Database\\Seeders\\SettingSeeder`** which sets the following values:

- **Primary Color:** `#1B2660`
- **Accent Color:** `#FFCD38`
- **Primary Font:** Poppins
- **Secondary Font:** Roboto
- **Home Page Heading:** "Welcome to the LCCD Integrated Information System"
- **Home Page Tagline:** "Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite."

The seed defaults also set `header_text` to "La Consolacion College Daet", `footer_text` to "Empowering Christ-centered digital transformation\n© {year} La Consolacion College Daet CMS", and `show_footer` to `true`.

## Approval Processes
Approval workflows ensure requests are reviewed by the proper people before they are finalized. Each module can define its own chain and scope it to a specific department.

### Creating an Approval Process
1. Open **Settings → Approval Processes** and click **New**.
2. Choose the **Module** from the dropdown (`requisitions`, `job_orders`, etc.) and select the **Department** from the list of user departments.
3. Save the process and add approval stages.
4. When editing a process, the same dropdowns are used for Module and Department.

### Adding Stages
1. From the process page click **Add Stage**.
2. Enter a stage **Name** and **Order**.
3. Optionally pick an assigned **User** for the step.
4. Repeat for each stage in the chain.

*A screenshot of the Approval Process editor is available in the ITRC Dropbox.*

### Workflow Impact
- Requisitions and job orders follow the stages for their module and department.
- If no process exists, submissions remain pending until manually handled.
- Other modules may reference these tables to implement similar approval flows.
- The seed defines a default three-step chain (Head → President → Finance), but
  you can reorder or add stages to meet each department's needs. Existing stages
  remain intact when a process is edited.
  When adjusting stage order, ensure each position number is unique starting at 1. If you reuse a position number, the new stage overwrites the previous one instead of merely hiding it.

Example command to load the default processes:

```bash
php artisan db:seed --class=ApprovalProcessSeeder
```

## Localization
Set how dates and times appear across the application:

- **Timezone** – server time zone for all schedules
- **Date Format** – choose `YYYY-MM-DD`, `MM/DD/YYYY`, etc.

Access this screen under **Settings → Localization**.

## Notifications
Manage the email alerts sent to users:

- **Email Toggles** – enable or disable notifications for tickets, job orders, and more
- **Templates** – customize the wording of outgoing messages. Template text is stored as Markdown and converted to HTML via the [league/commonmark](https://commonmark.thephpleague.com/) parser when emails are sent.

Visit **Settings → Notifications** to adjust these settings.

## Ticket Escalation
Automated SLA checks mark overdue tickets as escalated. Configure these options:

- **Enable Escalation** – toggle the background scheduler on or off
- **Check Interval** – how many minutes between SLA checks (1–60)

Visit **Settings → Ticket Escalation** (under the **General** section) to adjust these settings.

## Email Configuration
Specify how the application sends outbound messages:

- **SMTP Host/Port** – mail server connection details
- **Username/Password** – credentials if required by your mail service
- **Encryption** – transport scheme such as `tls` or `ssl`
- **From Address/Name** – default sender information

Navigate to **Settings → Email** (under **Communication**) to modify these values.

## Contact Information
Set a global phone number and email address displayed in notification footers and other communications.

- **Email** – main contact address for site inquiries
- **Phone** – optional hotline number or extension

Access this screen under **Settings → Contact Info**.

## Seeded Configuration Keys
The following settings are created by `Database\\Seeders\\SettingSeeder`:

| Key | Default Value |
| --- | ------------- |
| `color_primary` | `#1B2660` |
| `color_accent` | `#FFCD38` |
| `font_primary` | `Poppins` |
| `font_secondary` | `Roboto` |
| `home_heading` | "Welcome to the LCCD Integrated Information System" |
| `home_tagline` | "Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite." |
| `header_text` | "La Consolacion College Daet" |
| `footer_text` | "Empowering Christ-centered digital transformation\n© {year} La Consolacion College Daet CMS" |
| `show_footer` | `true` |
| `default_profile_photo` | value from `app.default_profile_photo` |
| `timezone` | `Asia/Manila` |
| `date_format` | `Y-m-d` |
| `notify_ticket_updates` | `true` |
| `notify_job_order_status` | `true` |
| `notify_requisition_status` | `true` |
| `notify_low_stock` | `true` |
| `template_ticket_updates` | `{{ message }}` |
| `template_job_order_status` | `{{ message }}` |
| `template_requisition_status` | `{{ message }}` |
| `template_low_stock` | `{{ message }}` |
| `sla_enabled` | `true` |
| `sla_interval` | `1` |
| `mail_host` | value from `mail.mailers.smtp.host` |
| `mail_port` | value from `mail.mailers.smtp.port` |
| `mail_username` | value from `mail.mailers.smtp.username` |
| `mail_password` | value from `mail.mailers.smtp.password` |
| `mail_encryption` | value from `mail.mailers.smtp.scheme` |
| `mail_from_address` | value from `mail.from.address` |
| `mail_from_name` | value from `mail.from.name` |
| `contact_email` | `null` |
| `contact_phone` | `null` |

## Additional Configuration Points
The following features are planned but not yet exposed in the System Settings UI:
- **Two-Factor Authentication** – toggling 2FA and choosing verification methods

These items may be added in future updates to improve flexibility.
