# 📝 Requisition Management Module – LCCD Integrated Information System

## 🌟 Purpose

The **Requisition Management Module** enables LCCD stakeholders to request materials, services, or assets needed for academic, administrative, or operational tasks. It streamlines request handling through structured workflows, transparent approvals, and efficient routing, ensuring timely delivery and responsible stewardship of institutional resources.

---

## 🤖 Core Features

### 1. Requests for Materials, Services, or Assets
- Users (faculty, staff, heads, president) can initiate requisitions for:
  - Office or teaching supplies
  - Facility or equipment repairs/services
  - Event support or operational needs
  - Any asset required for college operations
- Requisition forms include:
  - Item/service details (type, quantity, specifications)
  - Purpose/justification
  - Department and requester auto-filled
  - Optional attachments (quotations, specification sheets) stored with the request
  - Attachments may be up to 2 MB and are stored under `storage/app/public/requisition_attachments`. Only the requester can download them.

### 2. Multi-Level Approval Workflows
- The system enforces a structured chain depending on the requester’s role:
  - **Staff**: Department Head → President Department Head → Finance Department Head
  - **Department Head**: President Department Head → Finance Department Head
  - **President Department Head**: Finance Department Head only
- Each approval stage records **remarks** from the reviewer and is logged in the audit trail.
- Approvers simply advance the request to the next stage; the Finance office’s approval finalizes the requisition.

### 3. Automated Routing, Statuses, and Notifications
  - Workflow engine automatically routes requests to the next required approver.
  - Email notifications are sent to the next approver and to the requester whenever the status changes.
  - Notifications use `RequisitionStatusNotification` with templates configurable in **System Settings**.
  - Real-time status tracking uses these workflow states:
    - **pending_head** – awaiting department head review
    - **pending_president** – awaiting president approval
    - **pending_finance** – awaiting finance approval
    - **approved** – fully approved and ready for fulfillment
  - These status values correspond to constants in the `Requisition` model and control workflow transitions.
  - Once a requisition moves beyond **pending_head**, the requester can no longer edit it.
  - Approvers may return the request to **pending_head** with remarks when changes are needed, restarting the approval cycle. Requesters regain edit access only at this stage.
  - Requesters can monitor their requisitions and remarks via their dashboard.

### 4. Integration with Other Modules
  - **Job Order Module**: Created automatically when a job order needs materials not in stock and marked **approved** when the requisition is fulfilled.
  - **Inventory Module**: Approved requisitions check stock levels, deduct available quantities, and log the transaction.
  - **Purchase Order Module**: If requested item is out of stock, the system auto-generates a draft Purchase Order (PO) for Finance.
  - **Audit Trail**: Every approval, modification, and comment is stored for compliance.

---

## 🖼️ User Interface Design Notes

- Branded with LCCD and CCS logos, navy/gold/cyan Bootstrap 5 theme.
- **Single-page form** for new requisitions; dashboard for tracking current and historical requests.
- Visual stepper/progress bar shows approval stages.
- Approver interface highlights pending requests and actions required.
- Clear status badges and remarks are displayed at each step.

---

## 🔒 Security & Audit Considerations

- Only authenticated users may submit or act on requisitions.
- Permissions enforced at every workflow step.
- Immutable audit logs of all decisions and modifications.
- Sensitive requests (e.g., large-value, IT assets) flagged for admin review.

---

## 📊 Integration and Reporting

- KPI dashboard: Monitors average approval duration, number of modifications/returns, bottleneck stages.
- Export to Excel for finance, procurement, and QA purposes.
- Analytics for inventory utilization and procurement trends.

---

## ✨ Augustinian Value Alignment

| Value               | Implementation Example                                   |
|---------------------|----------------------------------------------------------|
| Unity               | Shared, transparent approval process involving all levels|
| Truth               | Full remarks, audit logs, and status tracking            |
| Competence          | Role-aware, automated routing and workflow efficiency    |
| Charity             | Serving needs across departments and roles               |
| Stewardship         | Resource request discipline and budget accountability    |
| Service             | Timely, visible, and fair handling of all requests       |
| Christ-Centeredness | Ethical, inclusive access to resources                   |

For more guides visit the [documentation index](README.md).

---

## 🚀 Navigation
- Previous: [Job Order Module](job-order-module.md)
- Next: [Inventory Module](inventory-module.md)
- [Documentation Index](README.md)

