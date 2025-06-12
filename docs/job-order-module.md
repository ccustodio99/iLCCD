# 🛠 Job Order Module – LCCD Integrated Information System

The **Job Order Module** lets LCCD stakeholders request repairs, installations,
and setups from a single form. It streamlines assignment and tracking so the
right department responds quickly and all actions are logged.

## 🎯 Purpose

This module ensures work requests move smoothly through approval,
assignment, and completion while keeping requesters informed. It also
ties into requisitions so needed materials are on hand before work
begins.

## 🧩 Core Features

1. **Requests for Various Job Types**
   - Faculty, staff, and heads submit job orders for installations,
     maintenance, audits, emergencies, and other needs.
   - The form captures job type, description, optional attachments, and
     auto-fills the requester and department.
   - Job order types are managed under **Settings → Job Order Types**.

2. **Post-Approval Assignment**
   - After multi-level approval (Head → President → Finance when
     required), jobs route automatically to the correct team—ITRC,
     maintenance, or another office.
   - Both the assignee and requester receive notifications.

3. **Requesting Materials**
   - When a job requires supplies, the system first checks **Inventory**.
   - Available stock is deducted immediately and noted on the job order.
   - If items are missing, a linked **Requisition** is created so the
     materials can be procured.
   - Job status syncs with the requisition so work only begins once
     materials arrive or are approved for purchase.
   - When the requisition reaches **approved**, the job order's status
     is automatically set to **approved** and the approval time is saved.

4. **Evaluation and Execution Logging**
   - Jobs move through clear statuses:
     Pending Head → Pending President → Pending Finance → Approved →
     Assigned → In Progress → Completed → Closed.
   - Requesters close the job order once work is verified done. The system
     records the `closed_at` time for reporting.
   - Assigned personnel log start and finish times, actions taken, and
     any feedback from the requester.
   - All activity is recorded in the audit trail for transparency.

## 🖼️ User Interface Design Notes

- Uses LCCD and CCS branding with navy, gold, and cyan colors.
- A single-page submission form and dashboard show job status and linked
  requisitions.
- Role-based views ensure requesters, approvers, and staff see only the
  actions relevant to them.

## 🔒 Security & Audit Considerations

- Only authenticated users may create or act on job orders.
- Every step—from submission to closure—is logged.
- Attachments are stored securely, and permission checks prevent
  unauthorized edits.

## 📊 Integration and Reporting

- Connects directly with the Requisition and Inventory modules.
- Feeds metrics such as completion time and material delays into the KPI
  dashboard.
- Reports can be exported for administration and finance review.

## ✨ Augustinian Value Alignment

| Value               | Implementation Example                             |
|---------------------|----------------------------------------------------|
| Unity               | Shared workflows, cross-department coordination    |
| Truth               | Complete records of all requests and fulfillment   |
| Competence          | Right people, right resources, timely completion   |
| Charity             | Service that addresses urgent needs and supports   |
| Stewardship         | Responsible use of institutional resources         |
| Service             | Reliable, accountable job order fulfillment        |
| Christ-Centeredness | Ethical, transparent response to community needs   |

---

## 🚀 Navigation
- Previous: [KPI & Audit Dashboard](kpi-audit-log-dashboard.md)
- Next: [Requisition Management Module](requisition-management-module.md)
- [Documentation Index](README.md)
