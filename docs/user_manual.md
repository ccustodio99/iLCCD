# 👤 User Manual – LCCD Integrated Information System

## 🎯 Purpose
The User Manual provides a step-by-step guide for everyday actions in the LCCD Integrated Information System. It explains how to log in, navigate modules, submit requests, and track progress. Each section links to detailed module documentation so users always know where to find help.

---

## 🚪 Logging In
1. Open your web browser and go to the system URL provided by the IT Resource Center.
2. Enter your **email** and **password**, then click **Login**.
3. If you forget your password, use the **Forgot Password** link to receive a reset email.
4. First‑time users should contact the administrator to create an account or enable self‑service registration if available.

---

## 🧭 Navigating the Dashboard
- After signing in, the dashboard shows shortcuts to Tickets, Job Orders, Requisitions, Inventory, Purchase Orders, and Documents.
- On small screens, use the hamburger button at the top of any page to open the navigation menu. On larger screens the menu is visible at all times.
- Email notifications are sent for approvals and assigned tasks. In-app
  header notifications are not yet implemented.
- Use the profile menu to update your **contact information** (e.g., phone number) or change your password.

---

## ✍️ Creating Requests
1. **Tickets** – Report issues or service needs. Fill in the category, subject, description, and optional due date. Attach files if necessary. Ticket subjects display as `[Category] - [Issue Summary] - Ticket ID`.
2. **Job Orders** – Request repairs or installations. First pick a **Type**, then choose a **Sub Type** from the list that appears. Provide job details and upload supporting documents.
3. **Requisitions** – Request supplies or equipment from Inventory. Enter item names, quantities, and justification.
4. **Purchase Orders** – Finance staff create purchase orders when requisitioned items are out of stock, low in stock, or when new items need to be purchased. Items can be selected from inventory or entered manually and then routed through approval.

Each module has status indicators so you can track approvals and fulfillment. See the individual module guides for full details.

---

## 📊 Tracking Progress
- The **My Tickets** or **My Requests** pages list everything you have submitted with their current status.
- Email alerts are sent whenever a ticket is created, assigned, updated, escalated, closed, or when someone comments. These notifications go to the ticket owner, the assigned user, and all watchers so everyone stays informed.
- Archiving a ticket removes it from the active list and automatically sets the status to **Closed**.
- After department approval of a job order or requisition, linked tickets
  and orders are locked from editing by the requester. Approvers can send
  them back to **pending** with remarks when revisions are needed.
- Staff who need to revise a closed or approved ticket may use the **Request Edit** action.
  A justification is required and the ticket status resets to **open** so the
  approval workflow restarts from the beginning.
- For managers, the dashboard highlights pending approvals with quick links to review them.
- The **KPI & Audit Dashboard** aggregates performance metrics like response times and completion rates. Access it via the **KPI Dashboard** link or by visiting `/kpi-dashboard`.

### Ticket Watchers
When you create a ticket, the system automatically adds watchers so the right people stay informed. These include your department head (or head of office) and IT administrators. You may add more users as watchers if they need visibility on the issue. The ticket owner and the assigned user can update and close the ticket. Watchers receive the same email notifications as the owner, and they can comment but cannot modify or close the ticket.

Example email:

```
Subject: Ticket #42 updated

The ticket "Printer not working" has been escalated.
```

---

## 🔒 Access and Permissions
Roles determine what you can see or modify. Typical roles include Admin, Department Head, and Staff. If you cannot access a feature you need, contact your administrator. For more details, see the [Access Control Module](Access_Control_Module.md).

---

## ✨ Augustinian Value Alignment
| Value | Everyday Practice |
|-------|------------------|
| Unity | Collaborate by using the same system across departments |
| Truth | Keep information accurate and complete in every request |
| Competence | Follow correct procedures to ensure efficient service |
| Charity | Support others by responding to their tickets promptly |
| Stewardship | Use resources wisely and track them through the system |
| Service | Prioritize the needs of students and colleagues |
| Christ-Centeredness | Work ethically and respectfully in all interactions |

---

## 🚀 Navigation
- Next: [Access Control Module](Access_Control_Module.md)
- [Documentation Index](README.md)
