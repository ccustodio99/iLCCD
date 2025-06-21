# 🧾 Purchase Order (PO) System – LCCD Integrated Information System

## 🎯 Purpose

The **Purchase Order (PO) System** manages the procurement workflow for out-of-stock or low-stock items as well as newly required goods. Purchase orders link directly to approved requisitions and inventory levels. The module ensures transparent, efficient purchasing, supports financial oversight, and helps maintain optimal stock for uninterrupted college operations.

---

## 🧩 Core Features

### 1. Automatic Generation for Out-of-Stock or Low-Stock Items
- When a requisition requires items that are unavailable or flagged as low stock, the system:
  - Lists the affected inventory items so finance staff can select them for purchase.
  - Automatically prepares a draft PO with item details and quantities.
  - Finance users may also add custom items that are not yet recorded in inventory.
  - Optionally consolidates similar requests for batch ordering.

### 2. Linkage to Approved Requisitions
- Every PO is directly linked to one or more approved requisitions, maintaining traceability from request to fulfillment.
- PO records display linked requisition numbers, requesting department, justification, and approval trail.

### 3. Workflow from PO Creation to Approval and Fulfillment
- **Creation:** PO is drafted from the requisition(s) or built manually by selecting low/out-of-stock items. Additional items can be entered directly and the purchase order is submitted for approval.
- **Approval:** The approval chain is configured in **Settings → Approval Processes**. The default flow routes to **Finance Department Staff**, then the **Finance Department Head**, and finally the **President Department Head**.
- **Order Placement:** Upon approval, PO is sent to the supplier; status is tracked as "Ordered."
- **Fulfillment:** Upon delivery, items are inspected and marked "Received" in the system.
- **Automatic Dates:** The system stamps the `ordered_at` and `received_at` fields when those statuses are set.
- **Status Tracking:** Dashboard tracks PO stages: Draft, Pending Approval, Approved, Ordered, Received, Closed, or Cancelled.

### 4. Inventory Updates Upon Receipt
- When PO items are received and accepted:
  - Inventory quantities are automatically updated ("stock in").
  - Item history logs receipt with date, supplier, and reference PO.
  - Notifications are sent to the original requestor and inventory custodian.
- Partial deliveries are supported; outstanding quantities remain tracked.

### 5. File Attachments for Supporting Documents
- Users in the Finance or Admin role may upload supplier quotations, invoices, or other files when creating or editing a PO.
- Uploaded files are limited to **2&nbsp;MB** and stored under `storage/app/public/purchase_order_attachments`.
- Owners and administrators can download the attachment from the PO listing; other users are denied access.

---

## 🖼️ User Interface Design Notes

- PO forms and status boards use LCCD/CCS branding and Bootstrap 5 design.
- Intuitive PO creation screen with autofill from requisitions and item master.
- Approval workflow screens display history, comments, and required actions.
- PO dashboard supports filtering by department, supplier, status, and date.

---

## 🔒 Security & Audit Considerations

- Only authorized roles (Purchasing, Finance, Admin) can create, approve, or close POs.
- All PO activities—creation, edits, approvals, receipts—are logged in the audit trail.
- Attachments (quotations, invoices, delivery receipts) are stored securely with each PO.
- Permission checks for high-value or restricted item purchases.
- Staff users may view only the orders they created, while admins can manage any order.

---

## 📊 Integration and Reporting

- Linked to Requisition, Inventory, and Audit modules for end-to-end traceability.
- KPI dashboard tracks PO lead time, approval duration, supplier performance, and fulfillment rates.
- Exportable reports for procurement, finance, and compliance review.

---

## ✨ Augustinian Value Alignment

| Value           | Implementation Example
|-----------------|---------------------------------------------------------------
| Unity           | Cross-departmental procurement and transparent fulfillment
| Truth           | Complete PO history, audit logs, and document trail
| Competence      | Efficient, policy-driven purchasing with automated workflows
| Charity         | Fair access to supplies for all departments
| Stewardship     | Responsible use and tracking of institutional funds
| Service         | Timely and accountable procurement process
| Christ-Centeredness | Ethical sourcing and integrity in all transactions

For more guides visit the [documentation index](README.md).

---

## 🚀 Navigation
- Previous: [Requisition Management Module](requisition-management-module.md)
- Next: [User Manual](user_manual.md)
- [Documentation Index](README.md)
