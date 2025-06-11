# 🎫 Ticketing System Module

The Ticketing System is the all-in-one portal for reporting issues and requesting services across LCCD. Students, faculty, and staff can log IT troubles, facility concerns, or document access needs from a single page. Each ticket auto-fills department details from the user profile and accepts optional attachments.

👉 **Smart routing:** Categories like IT, Facilities, or Documents send the ticket straight to the correct team. Department heads are notified instantly so nothing gets overlooked.

⏱️ **SLA monitoring:** Timers track how long tickets stay open. Critical requests escalate if they pass their deadline, ensuring urgent problems receive attention.

🔗 **Job orders & requisitions:** Complex issues may require a job order or extra supplies. Tickets can convert directly to job orders or link to requisitions, keeping workflow seamless. Progress updates reflect in the original ticket so users can follow along.

📊 **KPI integration:** Resolution times, escalation counts, and audit logs feed into the KPI dashboard for management insight. Every action is logged for accountability.

The interface uses Bootstrap 5 with official LCCD branding and is secured through the Access Control module. For more information on other modules, see the [documentation index](README.md).
### Current Implementation
- Users can create, edit, and delete their own tickets with category, subject, description, and due date.
- Tickets are listed on the My Tickets page.
- Automatic SLA monitoring escalates overdue tickets every minute.
- KPI logs capture escalation timestamps for dashboard reporting.
---
## 🚀 Navigation
- Previous: [User Interface & Branding](user-interface-branding.md)
- Next: [Document Management Module](document-management-module.md)
- [Documentation Index](README.md)

## Basic Workflow

1. Users create tickets specifying category, subject, description, and optional due date.
2. Tickets are listed on the "My Tickets" page with status tracking and simple editing.
3. Only the creator may modify or delete their tickets.
4. Tickets requiring maintenance or repairs convert to **Job Orders**.
5. If materials or tools are needed for the job, a linked **Requisition** is created.
6. Approved requisitions deduct items from **Inventory**; if none are available, a **Purchase Order** is generated for Finance.
7. All related documents, like approvals or receipts, are stored in the **Document Management** module.
