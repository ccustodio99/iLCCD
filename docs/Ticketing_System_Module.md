# 🎫 Ticketing System Module

The Ticketing System is the all-in-one portal for reporting issues and requesting services across LCCD. Students, faculty, and staff can log IT troubles, facility concerns, or document access needs from a single page. Each ticket auto-fills department details from the user profile and accepts optional attachments.

> **Setup note:** Run `php artisan storage:link` after migrations so ticket attachments can be served.

👉 **Smart routing:** Tickets start with large buttons for categories such as **Computers & Devices**, **Software & Apps**, **Network & Access**, **User Accounts & Access**, **Printing & Scanning**, **Procurement & Inventory**, **Facilities & Maintenance**, **Security & Safety**, **Training & Support**, **Feedback & Improvement**, and **Other / General Inquiry**. Selecting a button reveals its subcategories (progressive disclosure) so the request is routed to the right team. Department heads are notified instantly so nothing gets overlooked. Ticket categories are stored with parent → child relationships using a `parent_id` field.

⏱️ **SLA monitoring:** Timers track how long tickets stay open. Critical requests escalate if they pass their deadline, ensuring urgent problems receive attention.

🔗 **Job orders & requisitions:** Complex issues may require a job order or extra supplies. Tickets can convert directly to job orders or link to requisitions, keeping workflow seamless. Progress updates reflect in the original ticket so users can follow along.

📊 **KPI integration:** Resolution times, escalation counts, and audit logs feed into the KPI dashboard for management insight. Every action is logged for accountability.

The interface uses Bootstrap 5 with official LCCD branding and is secured through the Access Control module. For more information on other modules, see the [documentation index](README.md).
### Current Implementation
 - Users can create, edit, and archive their own tickets with category, subject, description, and due date. Archiving a ticket automatically marks it **Closed** and records the resolution time. Categories are selected using large buttons that reveal subcategories once chosen.
- A screenshot of the category picker is available in the ITRC Dropbox.

- Ticket subjects display as `[Category] - [Issue Summary] - Ticket ID` for easy reference.
- Tickets are listed on the My Tickets page.
- Users can filter tickets by status, category, assigned user, and choose whether to include archived tickets. The search box checks both the ticket subject and description.
- Automatic SLA monitoring escalates overdue tickets every minute.
- Ticket categories are configurable under **Settings → Ticket Categories**.
- KPI logs capture escalation timestamps for dashboard reporting.

## Ticket Assignment & Watchers
Tickets can be assigned to another user for resolution in addition to the ticket creator. The owner and the assigned user are allowed to update and close the ticket. When a ticket is filed, the following users are automatically added as **watchers** so they receive updates:

- The department head or head of office where the ticket originated
- IT or system administrators

Additional users may be included as watchers to collaborate on the issue. Watchers can follow progress and comment, but only the ticket owner and the assigned user can modify or close the ticket.
- After a department head approves a linked job order or requisition,
  the ticket becomes read-only for the requester. The head may revert the
  ticket to a pending state with remarks if updates are required.

### Email Alerts
The system emails stakeholders whenever a ticket is created, assigned, updated, escalated, closed, or when a new comment is posted. Alerts go to the ticket owner, the assigned user, and all watchers so everyone stays on the same page.

Example email:

```
Subject: Ticket #42 updated

The ticket "Printer not working" has been escalated.
View it in the portal to see details.
```
---
## 🚀 Navigation
- Previous: [User Interface & Branding](user-interface-branding.md)
- Next: [Document Management Module](document-management-module.md)
- [Documentation Index](README.md)

## Basic Workflow

1. Users create tickets specifying category, subject, description, and optional due date.
2. Tickets are listed on the "My Tickets" page with status tracking and simple editing.
   Each ticket has a **Details** popup showing audit history like when it was created, updated, escalated, or resolved.
3. Only the creator may modify or archive their tickets. Archiving also closes the ticket.
4. If further changes are needed after approval, the requester uses **Request Edit**.
   They must provide a justification and the ticket status resets to **open** so approvals start over.
5. Tickets requiring maintenance or repairs convert to **Job Orders**.
6. If materials or tools are needed for the job, the system checks **Inventory**. When stock is missing, it automatically creates a linked **Requisition**.
7. Once a requisition is approved, Inventory is checked again. Out‑of‑stock items trigger a **Purchase Order** for the Finance team; otherwise inventory is deducted.
8. All approvals and receipts are filed in **Document Management** so the ticket has a complete trail.
