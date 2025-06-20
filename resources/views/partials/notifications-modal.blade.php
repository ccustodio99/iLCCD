<div class="modal fade" id="notificationsModal" tabindex="-1" aria-labelledby="notificationsModalLabel" aria-hidden="true" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 id="notificationsModalLabel" class="modal-title">Notifications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @php($notifications = Auth::user()->unreadNotifications)
                @if($notifications->isEmpty())
                    <p class="text-muted">No notifications available.</p>
                @else
                    <ul class="list-group">
                        @foreach($notifications as $notification)
                            <li class="list-group-item">{{ $notification->data['message'] ?? '' }}</li>
                        @endforeach
                    </ul>
                    <a href="{{ route('notifications.index') }}" class="d-block mt-2">View all</a>
                @endif
            </div>
        </div>
    </div>
</div>
