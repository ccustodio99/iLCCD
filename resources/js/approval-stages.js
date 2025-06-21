import './bootstrap';

function initStageForms() {
    const table = document.getElementById('stages-table');
    if (!table) return;
    const stagesUrl = table.dataset.stagesUrl;

    function showFlash(message) {
        const container = document.getElementById('flash-container');
        if (!container || !message) return;
        const div = document.createElement('div');
        div.className = 'alert alert-success alert-dismissible fade show';
        div.setAttribute('role', 'alert');
        div.innerHTML = `${message}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>`;
        container.appendChild(div);
    }

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
        }).then(resp => {
            if (resp.data.message) {
                showFlash(resp.data.message);
            }
            reload();
        }).catch(err => console.error(err));
    }

    function attachHandlers() {
        table
            .querySelectorAll('form.stage-update-form, form.stage-delete-form, form.stage-form')
            .forEach(form => {
                form.addEventListener('submit', handleSubmit);
            });
    }

    attachHandlers();
}

(function () {
    initStageForms();
})();
