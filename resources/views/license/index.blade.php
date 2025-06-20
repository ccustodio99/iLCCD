@php($hideHeader = true)
@extends('layouts.guest')

@section('title', 'License Activation')

@section('content')
<div class="modal show d-block" tabindex="-1" id="licenseModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">License Activation</h1>
            </div>
            <div class="modal-body">

    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            @if (session('status') === 'License activated')
                <div class="mt-2">Redirecting to home in <span id="license-countdown">30</span> seconds...</div>
            @endif
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

    <form method="POST" action="{{ $license ? route('license.renew') : route('license.activate') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="license_text" class="form-label">License Text</label>
            <textarea id="license_text" name="license_text" rows="4" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label for="license_file" class="form-label">License File</label>
            <input id="license_file" type="file" name="license_file" class="form-control">
        </div>
        <div class="d-grid">
            <button type="submit" class="btn cta">{{ $license ? 'Renew License' : 'Activate License' }}</button>
        </div>
    </form>
    @if($license)
        <form method="POST" action="{{ route('license.destroy') }}" class="mt-3 text-center">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Remove License</button>
        </form>
    @endif
</div>
</div>
</div>
</div>
@endsection

@push('scripts')
@if (session('status') === 'License activated')
<script>
    let countdown = 30;
    const el = document.getElementById('license-countdown');
    const timer = setInterval(() => {
        if (el && countdown > 0) {
            countdown--;
            el.textContent = countdown;
        }
        if (countdown <= 0) {
            clearInterval(timer);
            window.location.href = {{ json_encode(url('/')) }};
        }
    }, 1000);
</script>
@endif
@endpush
