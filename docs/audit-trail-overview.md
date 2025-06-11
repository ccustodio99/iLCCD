# 🔍 Audit Trail Overview – LCCD Integrated Information System

Every module in the LCCD Integrated Information System records key actions to a unified **audit trail**. These logs support accountability, reporting, and compliance across Tickets, Job Orders, Requisitions, Inventory, Purchase Orders, Document Management, and User Management.

## 📑 What Gets Logged
- **Record changes** – creation, updates (with field differences), approvals, returns, and deletions
- **User activity** – logins, profile edits, and permission changes
- **Workflow steps** – ticket escalations, job order status moves, requisition approvals

All logs capture the user, timestamp, IP address, module, affected record, and action performed. When a record is updated, the audit trail stores the specific fields that changed showing the previous and new values. Logs are stored in `storage/logs` and displayed in the [KPI & Audit Log Dashboard](kpi-audit-log-dashboard.md).

Each log entry may also include an optional **comment** describing the context of the action, such as new ticket watchers or assignment changes.

## 🧩 Module Highlights
- **Ticketing System** – Resolution times and escalations feed into the audit log. Each ticket has a details view listing creation, updates, and escalations.
- **Job Orders** – Start/finish times and all status updates are recorded for transparency.
- **Requisition Management** – Every approval, modification, and remark is stored as part of the audit trail.
- **Inventory** – Issuances, returns, and deductions appear in item history and the audit log.
- **Purchase Orders** – Creation, edits, approvals, and receipts generate audit entries.
- **Document Management** – Uploads, version changes, and downloads have immutable audit logs.
- **User Management** – Logins, role changes, and profile edits write to the audit trail.

Together these logs provide a full history of interactions within the system.

---

## 🚀 Navigation
- Previous: [Access Control Module](Access_Control_Module.md)
- Next: [Ticketing System Module](Ticketing_System_Module.md)
- [Documentation Index](README.md)
