@extends('layouts.app')

@section('content')
    <h1 class="h4 mb-4">Notifications</h1>
    @if($notifications->isEmpty())
        <p class="text-muted">No notifications available.</p>
    @else
        <ul class="list-group">
            @foreach($notifications as $notification)
                <li class="list-group-item">{{ $notification->data['message'] ?? '' }}</li>
            @endforeach
        </ul>
        {{ $notifications->links() }}
    @endif
@endsection
