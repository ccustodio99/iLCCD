// Category dropdown logic for ticket forms
import './bootstrap';

function initCategoryDropdown(form) {
    const catSelect = form.querySelector('.category-select');
    const subSelect = form.querySelector('.subcategory-select');
    if (!catSelect || !subSelect) return;

    let categories = window.ticketCategories || {};
    if (Object.keys(categories).length === 0) {
        try {
            categories = JSON.parse(catSelect.dataset.categories || '{}');
        } catch (e) {
            console.error('Failed to parse categories JSON', e);
        }
    }
    const selected = subSelect.dataset.selected;

    function populate(catId) {
        subSelect.innerHTML = '<option value="">Select option</option>';
        if (categories[catId]) {
            categories[catId].forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = sub.name;
                if (selected && selected == sub.id) {
                    opt.selected = true;
                }
                subSelect.appendChild(opt);
            });
        }
    }

    catSelect.addEventListener('change', () => {
        populate(catSelect.value);
    });

    if (catSelect.value) {
        populate(catSelect.value);
    } else if (selected) {
        for (const [pid, subs] of Object.entries(categories)) {
            if (subs.some(s => String(s.id) === String(selected))) {
                catSelect.value = pid;
                populate(pid);
                break;
            }
        }
    }
}

(function () {
    const setup = () => {
        document.querySelectorAll('.ticket-form').forEach(initCategoryDropdown);
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setup);
    } else {
        setup();
    }
})();
