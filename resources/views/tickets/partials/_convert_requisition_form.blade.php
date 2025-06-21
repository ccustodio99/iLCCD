<div class="mt-3">
    <h6>Convert to Requisition</h6>
    @if ($errors->convertRequisition->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->convertRequisition->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form action="{{ route('tickets.requisition', $ticket) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div id="req-items-container">
            <div class="row g-2 mb-2 req-item-row">
                <div class="col-md-6">
                    <label class="form-label">Item</label>
                    <input type="text" name="item[]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Quantity</label>
                    <input type="number" name="quantity[]" class="form-control" value="1" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Specification</label>
                    <input type="text" name="specification[]" class="form-control">
                </div>
            </div>
        </div>
        <button type="button" id="req-add-item" class="btn btn-secondary btn-sm mb-2">Add Item</button>
        <div class="mb-2">
            <label class="form-label">Purpose</label>
            <textarea name="purpose" class="form-control" rows="3" required></textarea>
        </div>
        <div class="mb-2">
            <label class="form-label">Remarks</label>
            <textarea name="remarks" class="form-control" rows="2"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Attachment</label>
            <input type="file" name="attachment" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary btn-sm">Submit Requisition</button>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addBtn = document.getElementById('req-add-item');
        const container = document.getElementById('req-items-container');
        addBtn.addEventListener('click', function () {
            const row = container.querySelector('.req-item-row').cloneNode(true);
            row.querySelectorAll('input').forEach(i => i.value = i.type === 'number' ? 1 : '');
            container.appendChild(row);
        });
    });
</script>
