@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px;">
    <h1 class="mb-4">Edit User</h1>
    <form action="{{ route('users.update', $user) }}" method="POST">
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
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select id="role" name="role" class="form-select">
                @foreach ($roles as $role)
                    <option value="{{ $role }}" @selected(old('role', $user->role) === $role)>{{ ucfirst($role) }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="department" class="form-label">Department</label>
            <input id="department" type="text" name="department" class="form-control" value="{{ old('department', $user->department) }}">
        </div>
        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input id="is_active" class="form-check-input" type="checkbox" name="is_active" value="1" @checked(old('is_active', $user->is_active))>
            <label class="form-check-label" for="is_active">Active</label>
        </div>
        <button type="submit" class="btn cta">Update</button>
    </form>
</div>
@endsection
