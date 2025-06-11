# 📚 LCCD Integrated Information System

> *Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.*

---

## 📖 Table of Contents

- [Project Overview](#project-overview)
- [Purpose & Augustinian Values](#purpose--augustinian-values)
- [Key Stakeholders & Actors](#key-stakeholders--actors)
- [Local Setup](#local-setup)
- [System Modules](#system-modules)
- [System Flow](#system-flow)
- [Branding & User Experience](#branding--user-experience)
- [Access Control & Security](#access-control--security)
- [Directory Structure (Laravel)](#directory-structure-laravel)
- [Documentation & Resources](#documentation--resources)
- [Contributing](#contributing)
- [License](#license)

---

## 📢 Project Overview

The **LCCD Integrated Information System** is a custom-built PHP platform (Laravel style), designed for the 21st-century campus: secure, transparent, and aligned with Augustinian Catholic values. It unifies ticketing, job orders, requisitions, inventory, document management, analytics, and more—with *real* accountability, fast workflows, and beautiful, branded interfaces.

---

## 🌱 Purpose & Augustinian Values

**Mission:**  
Deliver a holistic, faith-driven digital backbone for LCCD, empowering every user—faculty, staff, student, admin—to serve, collaborate, and innovate with integrity.

**Core Values in Action:**  
- **Unity:** Central platform, cross-departmental flows
- **Truth:** Audit logs, transparent approvals, honest data
- **Competence:** Automated workflows, role-based access, error-free processes
- **Charity:** Accessible UX, prompt support, serving all users equitably
- **Stewardship:** Responsible use of funds, resources, and information
- **Service:** Fast fulfillment, clear feedback, actionable insights
- **Christ-Centeredness:** Ethical governance, dignity for all, inclusive by design

---

## 👥 Key Stakeholders & Actors

| Actor             | Role/Responsibility                                 |
|-------------------|----------------------------------------------------|
| President         | Strategic approvals, oversight                      |
| Finance Office    | Budget control, purchase orders, procurement        |
| Registrar         | Academic forms, student records                     |
| HR Department     | Staff, clearances, payroll                          |
| Clinic            | Medical incidents, reports                          |
| ITRC              | IT support, system admin                            |
| Department Heads  | Approvals, departmental management                  |
| Faculty/Staff     | Requests, tickets, job orders                       |
| Academic Units    | Academic/facility requests                          |

---

## 🔧 Local Setup

1. Copy `.env.example` to `.env` (see `.env` in the [Directory Structure](#directory-structure-laravel)).
2. Run `php artisan key:generate` to create the application encryption key.
3. Configure database settings in `.env` then run `php artisan migrate` to create the tables.

---

## 🧩 System Modules

### 1. 🎫 Ticketing System
- Issue reporting for IT, facilities, records, support
- Category-based auto-routing, SLA escalation, audit trail
- Integrates with job orders & requisitions

### 2. 🛠 Job Order Module
- Request, assign, and track repairs/installations/setups
- Approval, department assignment, evaluation, full execution logging
- Auto-generates linked requisitions if materials needed

### 3. 📝 Requisition Management
- Structured, multi-stage request/approval for materials, services, assets
- Auto-routes by user role; transparent status tracking; full audit trail
- Integrates with Inventory & PO modules

### 4. 📦 Inventory Module
- Live tracking of all assets/supplies; logs issuance/returns
- Auto-deducts on approved requisition; alerts for low/critical stock
- Dashboards, reporting, full audit history

### 5. 🧾 Purchase Order System
- Creates POs for out-of-stock/newly needed items, tied to requisitions
- Full approval & fulfillment workflow, auto-updates inventory on delivery
- Exportable analytics and supplier tracking

### 6. 📁 Document Management
- Versioned uploads of all key docs (policies, syllabi, reports)
- Role-based, secure access; links to requests; immutable audit logs

### 7. 📊 KPI & Audit Log Dashboard
- Tracks performance metrics: response, approval, fulfillment rates, bottlenecks
- Drill-down audit logs for every action; exports to Excel; role-based views

### 8. 👥 User Management
- Full account lifecycle: create, edit, activate/deactivate, bulk import
- Department & role assignment, permission matrix, audit trails
- Integrated with all workflows and dashboards

### 9. 🔐 Access Control
- Role-based security at UI & backend; session management, password policy
- Immutable logs, data encryption in transit & at rest

### 10. 🖥️ User Interface & Branding
- Unified LCCD/CCS identity, mobile-first, accessible, role-tailored navigation
- Consistent colors, fonts, logos, and feedback—all Gen Z ready

---

## 🔄 System Flow

```mermaid
flowchart TD
    A[User submits Ticket/Requisition/Job Order]
    B[System auto-routes by type/role]
    C[Multi-stage approvals: Head→President→Finance]
    D[Assignment (IT/Facilities/HR/etc)]
    E[Inventory/PO check—auto-deduct or create PO]
    F[Job/Order/Request executed]
    DM[Documents stored]
    G[KPI & audit logs updated]
    A --> B --> C --> D --> E --> F --> DM --> G
```
Every action is traceable; every step is value-aligned.

**Workflow summary:**
1. Ticket is created and may spawn a Job Order or Requisition.
2. Job Orders needing supplies automatically create a linked Requisition.
3. Approved Requisitions deduct from Inventory or trigger a Purchase Order if items are missing.
4. Documents like approvals and receipts are stored in the Document Management module.

---

## 🎨 Branding & User Experience

- **Logos:** `public/assets/images/LCCD.jpg`, `public/assets/images/CCS.jpg`
- **Colors:** Navy Blue (#1B2660), Gold (#FFCD38), CCS Cyan (#20BFEA), Red (#E5403A), White, Gray
- **Fonts:** Poppins, Roboto, Montserrat
- **UI:** Mobile-first, Bootstrap 5, intuitive role-based menus, quick actions, breadcrumbs
- **Accessibility:** WCAG 2.1 color contrast, keyboard navigation, screen reader friendly, alt text everywhere
- **Feedback:** Clear alerts, success/error icons, always inform next steps

---

## 🛡️ Access Control & Security

- **RBAC:** Strict, granular permission by role & department
- **Middleware:** `role` middleware protects sensitive routes
- **Secure login:** HTTPS, bcrypt/Argon2 hashing
- **Sessions:** Auto-logout (15 min inactivity), ID regeneration
- **CSRF protection:** On all sensitive forms
- **Audit logs:** Every action, permission change, and access attempt is tracked

---

## 📁 Directory Structure (Laravel)

```plaintext
/LCCD/
├── app/                 # Application code: Models, Http (Controllers/Middleware), Providers
│   ├── Console/
│   ├── Exceptions/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   ├── Models/
│   └── Providers/
├── bootstrap/           # App bootstrap files
├── config/              # Config files
├── database/            # Migrations, seeders, factories, SQLite data
├── public/              # Public assets (images, css, js) & index.php
├── resources/           # Views, Blade templates, language files
│   ├── views/
│   └── lang/
├── routes/              # Web and API route definitions
├── storage/             # Logs, cached files, uploaded docs, audit trails
│   ├── app/
│   ├── framework/
│   └── logs/
├── tests/               # Automated & manual tests
├── docs/                # Module docs, user guides, branding, audit, etc.
├── AGENTS.md            # Contributor & coding guidelines
└── .env                 # Environment config (not included in repo)
```

---

## 📚 Documentation & Resources

- [User Manual](docs/user_manual.md)
- [Access Control Module](docs/Access_Control_Module.md)
- [User Management Module](docs/user.md)
- [User Interface & Branding](docs/user-interface-branding.md)
- [Ticketing System Module](docs/Ticketing_System_Module.md)
- [Document Management Module](docs/document-management-module.md)
- [KPI & Audit Dashboard](docs/kpi-audit-log-dashboard.md)
- [Job Order Module](docs/job-order-module.md)
- [Requisition Management Module](docs/requisition-management-module.md)
- [Purchase Order System](docs/purchase-order-module.md)
- [Contributor Guidelines](AGENTS.md)
- Need help? Email: `itrc@lccd.edu.ph`

---

## 🤝 Contributing

- **How:**
  - Fork & branch from `main`
  - Follow AGENTS.md for code style, commit format, pull request protocol
  - Test your changes, include screenshots for UI
- **Commit message style:** `[Module] Short summary` (max 72 chars)
  - Example: `[Inventory] Fix stock deduction on requisition approval`
  - Optional Git hook: copy `githooks/commit-msg` to `.git/hooks/commit-msg` to enforce the format
- **Code checks:**
  - PHP: `php artisan test` (or `php -l file.php`)
  - HTML/CSS: [W3C Validator](https://validator.w3.org/)
  - Accessibility: [WAVE Tool](https://wave.webaim.org/)

---

## 📜 License

Released under the [MIT License](LICENSE).

---

## 🙏 Value Alignment

> *Rooted in Unity, Truth, Competence, Charity, Stewardship, Service, and Christ-centeredness. Every click and workflow is a leap toward an ethical, responsive, and socially transformative academic community.*

---

**Let’s build a future where tech and faith move as one. #ServeBoldly #CodeForGood 🚀✨**

---
