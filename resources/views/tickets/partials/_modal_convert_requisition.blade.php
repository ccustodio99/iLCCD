<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="convertRequisitionModalLabel{{ $ticket->id }}">Requisition</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            @include('tickets.partials._convert_requisition_form', ['ticket' => $ticket])
        </div>
    </div>
</div>
