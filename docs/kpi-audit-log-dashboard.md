# 📊 KPI & Audit Log Dashboard – LCCD Integrated Information System

## 🎯 Purpose
The **KPI & Audit Log Dashboard** provides actionable insights and transparent records of all critical processes within LCCD. By tracking key performance indicators (KPIs) and maintaining comprehensive audit logs, this module supports accountability, data-driven improvement, and compliance with institutional standards and policies.

Access the dashboard at `/kpi-dashboard` once logged in.

---

## 🧩 Core Features

### 1. Metrics at a Glance
- Displays the total number of **Tickets**, **Job Orders**, and **Requisitions**.
- Counts respect any applied date or department filters.

### 2. Filterable Audit Trail
- Search logs by **date range**, **user**, **department**, **module**, and **action**.
- Page size can be adjusted via the *Items per page* selector (5, 10, 20, 50).
- URL parameters:
  `date_from`, `date_to`, `user_id`, `department`, `module`, `action`, and `per_page`.

### 3. Excel Export for Administrators
- Admin users may download the currently filtered logs from `/kpi-dashboard/export`.
- The exported file is named `kpi_audit_logs.xlsx` for easy reference.
- Export respects the same query parameters as the dashboard filters.
- Access is restricted to users with the **admin** role.

### 4. Role-Based Dashboard Views
- Dashboards display only data relevant to the user’s role and department:
  - **Administrators/ITRC**: All data, all modules, full logs
  - **Department Heads**: Department-specific KPIs, their requests and actions
  - **Finance**: All financial and procurement processes
  - **President**: Institutional-wide overview and critical metrics
- Customizable widgets and quick filters for user-centric experience.

### 5. Detailed Audit Logs of All Actions, with Optional Remarks
- Every significant action (create, update, approve, reject, return, delete) is logged with:
  - Timestamp, user, department, module, affected record, action details
  - Optional remarks/comments for context (e.g., reason for rejection)
- Immutable logs support compliance, forensics, and institutional transparency.
- Audit trail viewer allows filtering by user, action, date, or module.

---

## 🖼️ User Interface Design Notes
- Branded dashboards and logs, using LCCD/CCS logos and institutional colors.
- Charts, graphs, and summary cards are powered by modern JS charting libraries (e.g., Chart.js, Recharts).
- Export and filter buttons are prominent and accessible.
- Detailed action log views with clickable details for drill-down.

---

## 🔒 Security & Audit Considerations
- Only authenticated users can view dashboards; data is limited by role.
- Exported files are watermarked for institutional use.
- Audit logs cannot be modified or deleted by users; only system admin can archive.
- Optional alerts for anomalies (e.g., repeated failed logins, unusual delays).

---

## 📊 Integration and Reporting
- Pulls data from all modules: Tickets, Job Orders, Requisitions, POs, Inventory, etc.
- Supports accreditation, compliance, and QA reviews.
- Enables cross-module performance monitoring and strategic planning.

---

## ✨ Augustinian Value Alignment
| Value           | Implementation Example                                           |
|-----------------|-----------------------------------------------------------------|
| Unity           | Common dashboard for all units, facilitating collaboration      |
| Truth           | Honest, transparent records and performance indicators          |
| Competence      | Data-driven, efficient administration and continuous improvement|
| Charity         | Fair feedback, context via remarks and comments                 |
| Stewardship     | Responsible use of information for improvement and compliance   |
| Service         | Responsive support and actionable insights for all stakeholders |
| Christ-Centeredness | Integrity, fairness, and human dignity in all records      |

For more guides visit the [documentation index](README.md).

---

## 🚀 Navigation
- Previous: [Document KPI & Log Dashboard](document-kpi-log-dashboard.md)
- Next: [Job Order Module](job-order-module.md)
- [Documentation Index](README.md)
