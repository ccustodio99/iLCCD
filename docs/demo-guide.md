# 🎬 Demo Guide – LCCD Integrated Information System

## 🚀 Purpose
This guide provides a structured approach for demonstrating the LCCD Integrated Information System. It includes a step-by-step flow, sample scripts, credentials, data, and scenarios for showcasing key features.

---

## 1️⃣ Demo Flow
1. **Clone the repository** and install dependencies.
2. **Prepare the environment** using the provided `.env.example` as a template.
3. **Run database migrations and seeders** (`php artisan migrate --seed`) to load the demo dataset.
4. **Launch the local server** and log in with the demo accounts.
5. **Walk through core modules** such as Ticketing, Job Orders, and Inventory.
6. **Highlight approval workflows, notifications, and audit logs.**

---

## 2️⃣ Scripts to Run
```bash
# Install PHP and JS dependencies
composer install
npm install && npm run build

# Migrate and seed demo data
php artisan migrate --seed # runs DemoSeeder
# (DocumentCategorySeeder runs first to create default categories)

# Start the local server
php artisan serve
```
These commands assume a local development setup with PHP, Composer, Node.js, and npm installed.

---

## 3️⃣ Demo Credentials
| Role  | Email               | Password |
|-------|---------------------|----------|
| Admin        | admin@example.com      | Password1 |
| President    | president@example.com  | Password1 |
| Finance      | finance@example.com    | Password1 |
| Registrar    | registrar@example.com  | Password1 |
| HR           | hr@example.com         | Password1 |
| Clinic       | clinic@example.com     | Password1 |
| ITRC Admin   | itrc@example.com       | Password1 |
| Department Head | head@example.com    | Password1 |
| Faculty/Staff| faculty@example.com    | Password1 |
| Academic Unit| academic@example.com   | Password1 |
| Staff        | staff@example.com      | Password1 |
| User         | user@example.com       | Password1 |

*Passwords are intentionally generic for demo purposes only.*

---

## 4️⃣ Demo Data
Running the seeders will create demo accounts for all major roles and sample records for every module. The default dataset now includes **five tickets** *(one archived and closed)*, **three job orders**, **four requisitions**, **eight inventory items**, **two purchase orders**, and **three documents with version history**. Each record also creates sample **ticket comments**, **requisition items**, and **inventory transactions** with matching audit logs. Modify `database/seeders/DemoSeeder.php` if you want to adjust the content.
The demo seeder reuses the document categories seeded by `DocumentCategorySeeder` so demo documents fall under **Policies & Procedures**, **Forms & Templates**, and **Course Materials**.

---

## 5️⃣ Demo Scenarios
1. **Ticket Creation and Escalation** – Submit a ticket as a user, then demonstrate automated SLA escalation.
2. **Job Order Workflow** – Convert a ticket into a job order and show approval steps.
3. **Inventory Checkout** – Issue an item from inventory and display stock updates.
4. **Access Control** – Log in as different roles to illustrate permissions.

---

## 6️⃣ Additional Tips
- Reset the database with `php artisan migrate:fresh --seed` if you need a clean slate.
- Emphasize branding elements (logos, colors, fonts) during the walkthrough.
- Keep the `.env` file out of version control and never expose real credentials.


---

## 🚀 Navigation
- Previous: [Inventory Module](inventory-module.md)
- Next: [Purchase Order System](purchase-order-module.md)
- [Documentation Index](README.md)
