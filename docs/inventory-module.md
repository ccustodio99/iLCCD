# 📦 Inventory Module – LCCD Integrated Information System

## 🌟 Purpose

The **Inventory Module** enables real-time tracking and management of all institutional supplies, equipment, and assets. It promotes transparency and stewardship by logging every issuance and return, providing automated stock updates, and proactively alerting stakeholders about low or critical inventory levels.

---

## 🧐 Core Features

### 1. Tracking of Item Quantities
- All supplies, equipment, and assets are catalogued in the inventory system.
- Key details: item name, description, category (`inventory_category_id`), department, location, supplier, purchase date, quantity on hand.
- Inventory categories are configurable under **Settings → Inventory Categories**.
- Item status: available, reserved, under maintenance, retired/disposed.

### 2. Logging of Issuances and Returns
- Every issuance of an item (for requisition, job order, or loan) is logged with:
  - Requestor, department, date/time, quantity issued, purpose.
- Returns (full/partial) are likewise logged, updating available quantity and item status.
- Each transaction is linked to the relevant module (Requisition, Job Order) for full traceability.
**Steps to issue an item:**
1. Open the item's **Details** modal and fill the **Issue** form.
2. Submit to deduct stock and record an `issue` transaction.

**Steps to return an item:**
1. Use the **Return** form in the item modal.
2. Submit to add back stock and record a `return` transaction.


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
  - Sends email notifications to department staff and the department head when stock is low
  - Notification recipients and the message template can be configured under **Settings → Notifications**
- Dashboards and reports feature visual alerts and quick links to restock or reorder.
- Rows on the dashboard turn yellow for low stock and red when items are depleted.
- Email alerts are sent automatically when stock drops to or below the minimum level.

### 5. Managing Inventory Categories
- Categories are created and edited under **Settings → Inventory Categories**.
- Only **Admin** users can add, disable, or delete categories.
- Items link to a category via the `inventory_category_id` field, and only active categories show in forms.

#### Parent & Subcategories
Each category may optionally reference another category via a `parent_id` field. This lets administrators build a hierarchy (for example **Electronics** → **Computers** → **Laptops**).

- **Create a parent category:** leave the **Parent** dropdown blank when adding a new category in the settings screen.
- **Create a subcategory:** choose the desired parent from the **Parent** dropdown while adding or editing the record.
- Only categories marked **active** appear in dropdowns and filters throughout the module so users see a tidy list.
- Administrators can review and reorder the hierarchy from the [System Settings](system-settings.md) page.

---

## 🖼️ User Interface Design Notes

- Branded with LCCD/CCS logos and official colors; Bootstrap 5-based responsive tables and cards.
- Central inventory dashboard shows item categories, stock status, alerts.
- Filters at the top of the list let you search by item name **or description** and narrow by category or status. Low stock filters surface items needing attention.
- “Add Item,” “Issue Item,” and “Return Item” actions are prominent for authorized roles.
- Visual badges for item status (in stock, low, out, reserved, maintenance).

---

## 🔒 Security & Audit Considerations

- Only users with the **Admin** or **ITRC** role may manage inventory items and perform issuing or returning.
- Inventory categories are maintained by **Admin** users only.
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
