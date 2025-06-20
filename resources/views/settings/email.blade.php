@extends('layouts.app')

@section('title', 'Email Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Email']
    ]])
    <h1 class="mb-4">Email Settings</h1>
    <form action="{{ route('settings.email.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="mail_host" class="form-label">SMTP Host</label>
            <input type="text" id="mail_host" name="mail_host" class="form-control" value="{{ setting('mail_host', config('mail.mailers.smtp.host')) }}">
        </div>
        <div class="mb-3">
            <label for="mail_port" class="form-label">SMTP Port</label>
            <input type="number" id="mail_port" name="mail_port" class="form-control" value="{{ setting('mail_port', config('mail.mailers.smtp.port')) }}">
        </div>
        <div class="mb-3">
            <label for="mail_username" class="form-label">Username</label>
            <input type="text" id="mail_username" name="mail_username" class="form-control" value="{{ setting('mail_username', config('mail.mailers.smtp.username')) }}">
        </div>
        <div class="mb-3">
            <label for="mail_password" class="form-label">Password</label>
            <input type="password" id="mail_password" name="mail_password" class="form-control" value="{{ setting('mail_password', config('mail.mailers.smtp.password')) }}">
        </div>
        <div class="mb-3">
            <label for="mail_encryption" class="form-label">Encryption</label>
            <input type="text" id="mail_encryption" name="mail_encryption" class="form-control" value="{{ setting('mail_encryption', config('mail.mailers.smtp.scheme')) }}">
        </div>
        <div class="mb-3">
            <label for="mail_from_address" class="form-label">From Address</label>
            <input type="email" id="mail_from_address" name="mail_from_address" class="form-control" value="{{ setting('mail_from_address', config('mail.from.address')) }}">
        </div>
        <div class="mb-3">
            <label for="mail_from_name" class="form-label">From Name</label>
            <input type="text" id="mail_from_name" name="mail_from_name" class="form-control" value="{{ setting('mail_from_name', config('mail.from.name')) }}">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
