<div class="modal fade" id="ticketCategoriesModal" tabindex="-1" aria-labelledby="ticketCategoriesModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="ticketCategoriesModalLabel" class="modal-title">Ticket Categories</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @include('components.per-page-selector')
                <div class="mb-3 d-flex flex-wrap gap-2">
                    <a href="{{ route('settings.index') }}" class="btn btn-secondary btn-sm">Back to Settings</a>
                    <a href="{{ route('ticket-categories.create') }}" class="btn btn-sm btn-primary">Add Category</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <caption class="visually-hidden">Ticket Categories</caption>
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Parent</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td>{{ optional($category->parent)->name }}</td>
                                <td>
                                    @if($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('ticket-categories.edit', $category) }}" class="btn btn-sm btn-primary">Edit</a>
                                    <form action="{{ route('ticket-categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $categories->links() }}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
