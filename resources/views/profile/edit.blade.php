@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="container" style="max-width: 500px;">
    <h1 class="mb-4">My Profile</h1>
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" aria-live="assertive">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="text-center mb-3">
        <img id="photo-preview" src="{{ $user->profile_photo_url }}" alt="Profile photo of {{ $user->name }}" class="rounded-circle mb-2" width="150" height="150">
        <p id="photo-help" class="form-text">Accepted formats: JPG, PNG, up to 2MB.</p>
    </div>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>
        <div class="mb-3">
            <label for="contact_info" class="form-label">Contact Information</label>
            <input id="contact_info" type="text" name="contact_info" class="form-control" value="{{ old('contact_info', $user->contact_info) }}">
            @error('contact_info')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="profile_photo" class="form-label">Profile Photo</label>
            <input id="profile_photo" type="file" name="profile_photo" class="form-control" accept="image/*" aria-describedby="photo-help">
        </div>
        <div class="form-check mb-3">
            <input id="remove_photo" type="checkbox" name="remove_photo" value="1" class="form-check-input">
            <label for="remove_photo" class="form-check-label">Remove current photo</label>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn cta me-2">Update</button>
        <a href="{{ route('profile.edit') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@push('scripts')
<script>
document.getElementById('profile_photo').addEventListener('change', function () {
    const [file] = this.files;
    if (file) {
        const preview = document.getElementById('photo-preview');
        preview.src = URL.createObjectURL(file);
    }
});
</script>
@endpush
@endsection
