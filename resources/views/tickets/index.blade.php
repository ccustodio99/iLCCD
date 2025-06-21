@extends('layouts.app')

@section('title', 'Tickets')

@section('content')
<div class="container">
    <h1 class="mb-4">My Tickets</h1>
    @include('components.per-page-selector')
    <div class="mb-3">
        @php
            $filterCat = request('ticket_category_id');
        @endphp
        <form method="GET" class="row row-cols-lg-auto g-2 align-items-end ticket-form">
            <div class="col">
                <label for="filter-status" class="form-label">Status</label>
                <select id="filter-status" name="status" class="form-select">
                    <option value="">Any</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ ucfirst($status) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label class="form-label">Category</label>
                <select id="filter-category" name="ticket_category_id" class="form-select">
                    <option value="">Any</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ (string)$filterCat === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-assigned" class="form-label">Assigned To</label>
                <select id="filter-assigned" name="assigned_to_id" class="form-select">
                    <option value="">Any</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" @selected((string)request('assigned_to_id') === (string)$u->id)>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col">
                <label for="filter-search" class="form-label">Search</label>
                <input id="filter-search" type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Subject or description">
            </div>
            <div class="col form-check mt-4">
                <input class="form-check-input" type="checkbox" value="1" id="filter-archived" name="archived" {{ request('archived') ? 'checked' : '' }}>
                <label class="form-check-label" for="filter-archived">Include Archived</label>
            </div>
            <div class="col">
                <button type="submit" class="btn btn-secondary">Filter</button>
            </div>
        </form>
    </div>
    <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#newTicketModal">New Ticket</button>
    <div class="table-responsive">
    <table class="table table-striped">
    <caption class="visually-hidden">Tickets</caption>
        <thead>
            <tr>
                <th>Category</th>
                <th>Subject</th>
                <th>Status</th>
                <th>Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tickets as $ticket)
            <tr>
                <td>{{ optional($ticket->ticketCategory)->name ?? 'N/A' }}</td>
                <td>{{ $ticket->formatted_subject }}</td>
                <td>{{ ucfirst($ticket->status) }}</td>
                <td>{{ optional($ticket->due_at)->format('Y-m-d') }}</td>
                <td>
                    <button type="button" class="btn btn-sm btn-info" data-details-url="{{ route('tickets.modal-details', $ticket) }}">Details</button>
                    @if (
                        $ticket->user_id === auth()->id() ||
                        $ticket->assigned_to_id === auth()->id() ||
                        (auth()->user()->role === 'head' && auth()->user()->department === $ticket->user->department)
                    )
                        <button type="button" class="btn btn-sm btn-primary ms-1" data-edit-url="{{ route('tickets.modal-edit', $ticket) }}">Edit</button>
                        @if($ticket->status !== 'converted')
                            <button type="button" class="btn btn-sm btn-outline-primary ms-1" data-convert-job-order-url="{{ route('tickets.modal-convert-job-order', $ticket) }}">Job Order</button>
                            <button type="button" class="btn btn-sm btn-outline-primary ms-1" data-convert-requisition-url="{{ route('tickets.modal-convert-requisition', $ticket) }}">Requisition</button>
                        @endif
                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="d-inline ms-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Archive this ticket?')">Archive</button>
                        </form>
                    @endif
                    @if($ticket->jobOrder)
                        <span class="visually-hidden">Job Order ID {{ $ticket->jobOrder->id }}</span>
                    @endif
                    @if($ticket->requisitions->count())
                        <span class="visually-hidden">Requisitions {{ $ticket->requisitions->pluck('id')->implode(' ') }}</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>

    {{ $tickets->links() }}

    <div class="modal fade" id="newTicketModal" tabindex="-1" aria-labelledby="newTicketModalLabel" aria-hidden="true" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="newTicketModalLabel" class="modal-title">New Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data" class="ticket-form">
                    @csrf
                    <div class="modal-body">
                        @php
                            $selectedModalSub = old('ticket_category_id');
                            $selectedModalCat = null;
                            foreach ($categories as $cat) {
                                if ($cat->children->contains('id', $selectedModalSub)) {
                                    $selectedModalCat = $cat->id;
                                    break;
                                }
                            }
                            $categoryData = $categories->mapWithKeys(function($cat) {
                                return [$cat->id => $cat->children->map(fn($c) => ['id' => $c->id, 'name' => $c->name])];
                            });
                        @endphp
                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <select class="form-select category-select mb-2" data-categories='@json($categoryData)' required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ (string)$selectedModalCat === (string)$cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                            <select name="ticket_category_id" class="form-select subcategory-select" data-selected="{{ $selectedModalSub }}" required></select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Subject</label>
                            <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Attachment</label>
                            <input type="file" name="attachment" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Assign To</label>
                            <select name="assigned_to_id" class="form-select">
                                <option value="">Unassigned</option>
                                @foreach($users as $u)
                                    <option value="{{ $u->id }}" {{ old('assigned_to_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Watchers</label>
                            <select name="watchers[]" class="form-select watcher-select" data-search-url="{{ route('users.search') }}" multiple>
                                @php($selectedWatchers = old('watchers', []))
                                @foreach(App\Models\User::whereIn('id', $selectedWatchers)->orderBy('name')->get() as $u)
                                    <option value="{{ $u->id }}" selected>{{ $u->name }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Search to add multiple users</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Due Date</label>
                            <input type="date" name="due_at" class="form-control" value="{{ old('due_at') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="dynamicTicketModal" tabindex="-1" aria-hidden="true" role="dialog"></div>
    @include('partials.category-dropdown-script')
    @include('partials.user-select-script')
    <script>
    (function () {
        const modalEl = document.getElementById('dynamicTicketModal');

        function renderModal(html) {
            modalEl.innerHTML = html;
            modalEl.querySelectorAll('script').forEach(oldScript => {
                const script = document.createElement('script');
                if (oldScript.src) {
                    script.src = oldScript.src;
                } else {
                    script.textContent = oldScript.textContent;
                }
                document.body.appendChild(script);
                script.remove();
            });
            new bootstrap.Modal(modalEl).show();
        }

        document.querySelectorAll('[data-details-url]').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.detailsUrl)
                    .then(r => r.text())
                    .then(renderModal);
            });
        });
       document.querySelectorAll('[data-edit-url]').forEach(btn => {
           btn.addEventListener('click', () => {
               fetch(btn.dataset.editUrl)
                   .then(r => r.text())
                   .then(renderModal);
           });
       });
        document.querySelectorAll('[data-convert-job-order-url]').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.convertJobOrderUrl)
                    .then(r => r.text())
                    .then(renderModal);
            });
        });
        document.querySelectorAll('[data-convert-requisition-url]').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(btn.dataset.convertRequisitionUrl)
                    .then(r => r.text())
                    .then(renderModal);
            });
        });
    })();
    </script>
</div>
@endsection
