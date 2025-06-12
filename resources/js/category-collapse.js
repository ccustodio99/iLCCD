// Category collapse logic for ticket forms
import './bootstrap';

function initCategoryForm(container) {
    const buttons = container.querySelectorAll('.category-btn');
    const selects = container.querySelectorAll('.subcategory-select');

    buttons.forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.bsTarget?.substring(1);
            container.querySelectorAll('.category-collapse').forEach(col => {
                if (col.id !== target) {
                    bootstrap.Collapse.getOrCreateInstance(col).hide();
                }
            });
        });
    });

    selects.forEach(sel => {
        sel.addEventListener('change', () => {
            const hidden = container.querySelector('input[name="ticket_category_id"]');
            if (hidden) {
                hidden.value = sel.value;
            }
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.ticket-form').forEach(initCategoryForm);
});
