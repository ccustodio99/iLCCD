import './bootstrap';

function initStageForms() {
    const table = document.getElementById('stages-table');
    if (!table) return;
    const stagesUrl = table.dataset.stagesUrl;

    async function reload() {
        try {
            const resp = await axios.get(stagesUrl);
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = resp.data.html;
            attachHandlers();
        } catch (e) {
            console.error(e);
        }
    }

    function handleSubmit(e) {
        e.preventDefault();
        const form = e.target;
        const data = new FormData(form);
        axios({
            method: form.method || 'POST',
            url: form.action,
            data,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        }).then(reload).catch(err => console.error(err));
    }

    function attachHandlers() {
        table.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', handleSubmit);
        });
    }

    attachHandlers();
}

document.addEventListener('DOMContentLoaded', initStageForms);
