# 🎫 Ticketing System Module

The Ticketing System is the all-in-one portal for reporting issues and requesting services across LCCD. Students, faculty, and staff can log IT troubles, facility concerns, or document access needs from a single page. Each ticket auto-fills department details from the user profile and accepts optional attachments.

> **Setup note:** Run `php artisan storage:link` after migrations so ticket attachments can be served.

👉 **Smart routing:** Categories like IT, Facilities, or Documents send the ticket straight to the correct team. Department heads are notified instantly so nothing gets overlooked.

⏱️ **SLA monitoring:** Timers track how long tickets stay open. Critical requests escalate if they pass their deadline, ensuring urgent problems receive attention.

🔗 **Job orders & requisitions:** Complex issues may require a job order or extra supplies. Tickets can convert directly to job orders or link to requisitions, keeping workflow seamless. Progress updates reflect in the original ticket so users can follow along.

📊 **KPI integration:** Resolution times, escalation counts, and audit logs feed into the KPI dashboard for management insight. Every action is logged for accountability.

The interface uses Bootstrap 5 with official LCCD branding and is secured through the Access Control module. For more information on other modules, see the [documentation index](README.md).
### Current Implementation
- Users can create, edit, and archive their own tickets with category, subject, description, and due date.
- Ticket subjects display as `[Category] - [Issue Summary] - Ticket ID` for easy reference.
- Tickets are listed on the My Tickets page.
- Automatic SLA monitoring escalates overdue tickets every minute.
- KPI logs capture escalation timestamps for dashboard reporting.

## Ticket Assignment & Watchers
Tickets can be assigned to another user for resolution in addition to the ticket creator. The owner and the assigned user are allowed to update and close the ticket. When a ticket is filed, the following users are automatically added as **watchers** so they receive updates:

- The department head or head of office where the ticket originated
- IT or system administrators

Additional users may be included as watchers to collaborate on the issue. Watchers can follow progress and comment, but only the ticket owner and the assigned user can modify or close the ticket.
---
## 🚀 Navigation
- Previous: [User Interface & Branding](user-interface-branding.md)
- Next: [Document Management Module](document-management-module.md)
- [Documentation Index](README.md)

## Basic Workflow

1. Users create tickets specifying category, subject, description, and optional due date.
2. Tickets are listed on the "My Tickets" page with status tracking and simple editing.
   Each ticket has a **Details** popup showing audit history like when it was created, updated, escalated, or resolved.
3. Only the creator may modify or archive their tickets.
4. Tickets requiring maintenance or repairs convert to **Job Orders**.
5. If materials or tools are needed for the job, the system checks **Inventory**. When stock is missing, it automatically creates a linked **Requisition**.
6. Once a requisition is approved, Inventory is checked again. Out‑of‑stock items trigger a **Purchase Order** for the Finance team; otherwise inventory is deducted.
7. All approvals and receipts are filed in **Document Management** so the ticket has a complete trail.
