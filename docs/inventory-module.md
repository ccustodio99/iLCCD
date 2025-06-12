# 📦 Inventory Module – LCCD Integrated Information System

## 🌟 Purpose

The **Inventory Module** enables real-time tracking and management of all institutional supplies, equipment, and assets. It promotes transparency and stewardship by logging every issuance and return, providing automated stock updates, and proactively alerting stakeholders about low or critical inventory levels.

---

## 🧐 Core Features

### 1. Tracking of Item Quantities
- All supplies, equipment, and assets are catalogued in the inventory system.
- Key details: item name, description, category, department, location, supplier, purchase date, quantity on hand.
- Item status: available, reserved, under maintenance, retired/disposed.

### 2. Logging of Issuances and Returns
- Every issuance of an item (for requisition, job order, or loan) is logged with:
  - Requestor, department, date/time, quantity issued, purpose.
- Returns (full/partial) are likewise logged, updating available quantity and item status.
- Each transaction is linked to the relevant module (Requisition, Job Order) for full traceability.

### 3. Automatic Deduction Upon Approved Requisitions
- When a requisition is approved, the corresponding items are auto-deducted from inventory.
- If stock is insufficient, the module can:
  - Block the transaction and notify the requester/approver.
  - Trigger creation of a Purchase Order (PO) for procurement.
- All deductions are visible in the audit log and on item history.

### 4. Stock Level Alerts for Low Inventory
- Each item has a configurable **minimum stock level**.
- The system continuously checks inventory and:
  - Flags items that fall below threshold (yellow/orange alert)
  - Flags out-of-stock items (red alert)
  - Sends notifications to custodians/department heads and, optionally, the purchasing office
- Dashboards and reports feature visual alerts and quick links to restock or reorder.

---

## 🖼️ User Interface Design Notes

- Branded with LCCD/CCS logos and official colors; Bootstrap 5-based responsive tables and cards.
- Central inventory dashboard shows item categories, stock status, alerts.
- Filters/search by item name, category, department, status.
- “Add Item,” “Issue Item,” and “Return Item” actions are prominent for authorized roles.
- Visual badges for item status (in stock, low, out, reserved, maintenance).

---

## 🔒 Security & Audit Considerations

- Only authenticated and authorized users can add, edit, issue, or return items.
- All transactions (issuance, return, edit, disposal) are audit-logged.
- Item history is immutable and accessible for compliance and review.
- Permission checks for sensitive/expensive items or critical equipment.

---

## 📊 Integration and Reporting

- Links with Requisition and Purchase Order modules for seamless asset flow.
- Inventory levels and activities feed into KPI and compliance dashboards.
- Exportable reports for department heads, procurement, and administration.
- Analytics on item usage, trends, and forecast needs.

---

## ✨ Augustinian Value Alignment

| Value               | Implementation Example                                   |
|---------------------|----------------------------------------------------------|
| Unity               | Centralized, transparent inventory for all departments   |
| Truth               | Real-time, accurate reporting of item movement           |
| Competence          | Automated, error-reducing stock management               |
| Charity             | Fast and fair resource allocation across the community   |
| Stewardship         | Proactive restocking, loss prevention, and accountability|
| Service             | Easy access and rapid fulfillment for legitimate needs   |
| Christ-Centeredness | Ethical and responsible use of school resources          |

For more guides visit the [documentation index](README.md).

---

## 🚀 Navigation
- Previous: [Requisition Management Module](requisition-management-module.md)
- Next: [Purchase Order System](purchase-order-module.md)
- [Documentation Index](README.md)
