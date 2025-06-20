@extends('layouts.app')

@section('title', 'License Activation')

@section('content')
<div class="container" style="max-width: 500px;">
    <h1 class="mb-4">License Activation</h1>

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($license)
        <p class="mb-3">
            Status:
            @if($license->isValid())
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-danger">Expired</span>
            @endif
            &mdash; Expires {{ $license->expires_at->toFormattedDateString() }}
        </p>
    @else
        <p class="mb-3"><span class="badge bg-danger">No active license</span></p>
    @endif

    <form method="POST" action="{{ $license ? route('license.renew') : route('license.activate') }}">
        @csrf
        <div class="mb-3">
            <label for="license" class="form-label">Encoded License</label>
            <input id="license" type="text" name="license" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn cta">{{ $license ? 'Renew License' : 'Activate License' }}</button>
        </div>
    </form>
</div>
@endsection
