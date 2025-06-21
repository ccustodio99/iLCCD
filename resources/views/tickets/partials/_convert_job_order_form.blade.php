<div class="mt-3">

    @if ($errors->convertJobOrder->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->convertJobOrder->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form action="{{ route('tickets.convert', $ticket) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-2">
            <label class="form-label">Type</label>
            <select name="type_parent" id="job_order_type_parent" class="form-select" required>
                <option value="">Select Type</option>
                @foreach($jobOrderTypes as $type)
                    <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-2">
            <label class="form-label">Sub Type</label>
            <select name="job_type" id="job_order_job_type" class="form-select" disabled required>
                <option value="">Select Sub Type</option>
            </select>
        </div>
        <div class="mb-2">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="3" required>{{ old('description', $ticket->description) }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Submit Job Order</button>
    </form>
</div>
<script>
    (function () {
        const parent = document.getElementById('job_order_type_parent');
        const child = document.getElementById('job_order_job_type');
        const typeMap = @json($typeMap ?? []);

        function loadChildren(id) {
            child.innerHTML = '<option value="">Select Sub Type</option>';
            if (!id) {
                child.disabled = true;
                return;
            }
            child.disabled = false;
            (typeMap[id] || []).forEach(c => {
                const opt = document.createElement('option');
                opt.value = c.name;
                opt.textContent = c.name;
                child.appendChild(opt);
            });
        }

        parent.addEventListener('change', () => loadChildren(parent.value));
        loadChildren(parent.value);
    })();
</script>
