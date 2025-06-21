import './bootstrap';

function sanitize(text) {
    const div = document.createElement('div');
    div.innerHTML = text ?? '';
    return div.textContent || '';
}

function createCell(content) {
    const td = document.createElement('td');
    td.textContent = sanitize(content);
    return td;
}

function renderTableBody(bodyId, rows) {
    const body = document.getElementById(bodyId);
    if (!body) return;
    body.innerHTML = '';
    if (!rows.length) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = body.parentElement.querySelectorAll('th').length;
        td.classList.add('text-center');
        td.textContent = 'No records';
        tr.appendChild(td);
        body.appendChild(tr);
        return;
    }
    rows.forEach(row => body.appendChild(row));
}

async function loadDashboardData() {
    try {
        // Preserve current query params so filters and pagination remain intact
        const params = window.location.search;
        const response = await axios.get(`/dashboard/data${params}`);
        const data = response.data;

        if (data.tickets && data.tickets.data) {
            const rows = data.tickets.data.map(t => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(t.formatted_subject));
                tr.appendChild(createCell(t.status.charAt(0).toUpperCase() + t.status.slice(1)));
                tr.appendChild(createCell(t.due_at ? t.due_at.substring(0, 10) : ''));
                return tr;
            });
            renderTableBody('tickets-body', rows);
        }
        if (data.jobOrders && data.jobOrders.data) {
            const rows = data.jobOrders.data.map(o => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(o.job_type));
                tr.appendChild(createCell(o.description.slice(0, 50)));
                tr.appendChild(createCell(o.status.charAt(0).toUpperCase() + o.status.slice(1)));
                return tr;
            });
            renderTableBody('job-orders-body', rows);
        }
        if (data.requisitions && data.requisitions.data) {
            const rows = data.requisitions.data.map(r => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(r.purpose.slice(0, 50)));
                tr.appendChild(createCell(r.status.replace('_', ' ')));
                return tr;
            });
            renderTableBody('requisitions-body', rows);
        }
        if (data.purchaseOrders && data.purchaseOrders.data) {
            const rows = data.purchaseOrders.data.map(p => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(p.item));
                tr.appendChild(createCell(p.quantity));
                tr.appendChild(createCell(p.status.charAt(0).toUpperCase() + p.status.slice(1)));
                return tr;
            });
            renderTableBody('purchase-orders-body', rows);
        }
        if (data.incomingDocuments && data.incomingDocuments.data) {
            const rows = data.incomingDocuments.data.map(d => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(d.document.title));
                tr.appendChild(createCell(d.user.name));
                return tr;
            });
            renderTableBody('incoming-docs-body', rows);
        }
        if (data.outgoingDocuments && data.outgoingDocuments.data) {
            const rows = data.outgoingDocuments.data.map(d => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(d.document.title));
                tr.appendChild(createCell(d.user.name));
                return tr;
            });
            renderTableBody('outgoing-docs-body', rows);
        }
        if (data.forApprovalDocuments && data.forApprovalDocuments.data) {
            const rows = data.forApprovalDocuments.data.map(d => {
                const tr = document.createElement('tr');
                tr.appendChild(createCell(d.document.title));
                tr.appendChild(createCell(d.user.name));
                return tr;
            });
            renderTableBody('for-approval-body', rows);
        }
        const statusEl = document.getElementById('dashboard-status');
        if (statusEl) {
            statusEl.textContent = 'Dashboard updated';
        }
    } catch (e) {
        console.error(e);
    }
}

(function () {
    if (document.getElementById('tickets-table')) {
        loadDashboardData();
        setInterval(loadDashboardData, 300000);
    }
})();
