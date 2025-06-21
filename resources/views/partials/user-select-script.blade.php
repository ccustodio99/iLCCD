<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.3.0/dist/css/tom-select.css">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.0/dist/js/tom-select.complete.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.watcher-select').forEach(function (el) {
            new TomSelect(el, {
                valueField: 'id',
                labelField: 'text',
                searchField: 'text',
                load: function(query, callback) {
                    if (!query.length) return callback();
                    fetch(el.dataset.searchUrl + '?q=' + encodeURIComponent(query), {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(function (resp) { return resp.json(); })
                        .then(function (json) { callback(json.results || []); })
                        .catch(function () { callback(); });
                }
            });
        });
    });
</script>
