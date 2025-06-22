# 🔄 Ticket Workflow Sequence

This UML sequence diagram illustrates how a ticket flows from creation to resolution. It highlights approvals and inventory checks typical across modules.

```mermaid
sequenceDiagram
    participant User
    participant System
    participant Head as Dept\ Head
    participant Staff
    participant Inventory
    User->>System: Submit ticket
    System->>Head: Request approval
    Head-->>System: Approve or reject
    System->>Staff: Assign task
    Staff->>Inventory: Check stock
    Inventory-->>Staff: Provide items
    Staff->>System: Complete work
    System-->>User: Notify completion
```

*Note: Finance or President approvals may occur depending on the requisition amount.*
