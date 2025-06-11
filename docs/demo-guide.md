# 🎬 Demo Guide – LCCD Integrated Information System

## 🚀 Purpose
This guide provides a structured approach for demonstrating the LCCD Integrated Information System. It includes a step-by-step flow, sample scripts, credentials, data, and scenarios for showcasing key features.

---

## 1️⃣ Demo Flow
1. **Clone the repository** and install dependencies.
2. **Prepare the environment** using the provided `.env.example` as a template.
3. **Run database migrations and seeders** to populate demo data.
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
php artisan migrate --seed

# Start the local server
php artisan serve
```
These commands assume a local development setup with PHP, Composer, Node.js, and npm installed.

---

## 3️⃣ Demo Credentials
| Role  | Email               | Password |
|-------|---------------------|----------|
| Admin | admin@example.com   | Password1 |
| Staff | staff@example.com   | Password1 |
| User  | user@example.com    | Password1 |

*Passwords are intentionally generic for demo purposes only.*

---

## 4️⃣ Demo Data
The seeders create sample tickets, job orders, requisitions, and inventory items. You can modify the seed files in `database/seeders/` to tailor the demo content.

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

