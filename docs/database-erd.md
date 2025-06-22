# 🗄️ Database ER Diagram

This diagram summarizes the main tables and relationships in the LCCD Integrated Information System. For clarity it focuses on the core modules used across Tickets, Job Orders, Requisitions, Inventory, Purchase Orders, and Document Management.

```mermaid
erDiagram
    USERS {
        BIGINT id PK
        STRING name
        STRING email
    }

    TICKET_CATEGORIES {
        BIGINT id PK
        BIGINT parent_id FK
        STRING name
    }

    TICKETS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT assigned_to_id FK
        BIGINT ticket_category_id FK
        STRING subject
    }

    TICKET_COMMENTS {
        BIGINT id PK
        BIGINT ticket_id FK
        BIGINT user_id FK
    }

    TICKET_WATCHERS {
        BIGINT id PK
        BIGINT ticket_id FK
        BIGINT user_id FK
    }

    JOB_ORDERS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT ticket_id FK
        BIGINT assigned_to_id FK
    }

    REQUISITIONS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT ticket_id FK
        BIGINT job_order_id FK
        BIGINT approved_by_id FK
    }

    REQUISITION_ITEMS {
        BIGINT id PK
        BIGINT requisition_id FK
        STRING item
    }

    PURCHASE_ORDERS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT requisition_id FK
        BIGINT inventory_item_id FK
    }

    INVENTORY_CATEGORIES {
        BIGINT id PK
        BIGINT parent_id FK
        STRING name
    }

    INVENTORY_ITEMS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT inventory_category_id FK
        STRING name
    }

    DOCUMENT_CATEGORIES {
        BIGINT id PK
        BIGINT parent_id FK
        STRING name
    }

    DOCUMENTS {
        BIGINT id PK
        BIGINT user_id FK
        BIGINT document_category_id FK
        STRING title
    }

    DOCUMENT_VERSIONS {
        BIGINT id PK
        BIGINT document_id FK
        BIGINT uploaded_by FK
        INT version
    }

    DOCUMENT_LOGS {
        BIGINT id PK
        BIGINT document_id FK
        BIGINT user_id FK
    }

    APPROVAL_PROCESSES {
        BIGINT id PK
        STRING module
    }

    APPROVAL_STAGES {
        BIGINT id PK
        BIGINT approval_process_id FK
        BIGINT assigned_user_id FK
    }

    USERS ||--o{ TICKETS : files
    USERS ||--o{ JOB_ORDERS : requests
    USERS ||--o{ REQUISITIONS : requests
    USERS ||--o{ PURCHASE_ORDERS : creates
    USERS ||--o{ INVENTORY_ITEMS : owns
    USERS ||--o{ DOCUMENTS : uploads
    USERS ||--o{ DOCUMENT_VERSIONS : uploads
    USERS ||--o{ DOCUMENT_LOGS : logs
    USERS ||--o{ TICKET_COMMENTS : writes
    USERS ||--o{ TICKET_WATCHERS : watches
    TICKET_CATEGORIES ||--o{ TICKETS : categorizes
    TICKETS ||--|{ TICKET_COMMENTS : has
    TICKETS ||--|{ TICKET_WATCHERS : has
    TICKETS ||--|| JOB_ORDERS : converts
    TICKETS ||--o{ REQUISITIONS : creates
    JOB_ORDERS ||--o{ REQUISITIONS : includes
    REQUISITIONS ||--|{ REQUISITION_ITEMS : contains
    REQUISITIONS ||--o{ PURCHASE_ORDERS : triggers
    INVENTORY_CATEGORIES ||--o{ INVENTORY_ITEMS : classifies
    PURCHASE_ORDERS }o--|| INVENTORY_ITEMS : stocks
    DOCUMENT_CATEGORIES ||--o{ DOCUMENTS : groups
    DOCUMENTS ||--|{ DOCUMENT_VERSIONS : versioned
    DOCUMENTS ||--|{ DOCUMENT_LOGS : activity
    APPROVAL_PROCESSES ||--|{ APPROVAL_STAGES : has
```

*Note: Not all columns are shown. This simplified ERD highlights the primary keys and foreign key relationships used across the major modules.*
