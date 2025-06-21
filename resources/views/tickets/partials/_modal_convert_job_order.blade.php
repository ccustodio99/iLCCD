<div class="modal fade" id="convertJobOrderModal{{ $ticket->id }}" tabindex="-1" aria-labelledby="convertJobOrderModalLabel{{ $ticket->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="convertJobOrderModalLabel{{ $ticket->id }}">Convert to Job Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('tickets.partials._convert_job_order_form', ['ticket' => $ticket, 'jobOrderTypes' => $jobOrderTypes, 'typeMap' => $typeMap])
            </div>
        </div>
    </div>
</div>
