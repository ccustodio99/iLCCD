@extends('layouts.app')

@section('title', 'Ticket Escalation')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Ticket Escalation']
    ]])
    <h1 class="mb-4">Ticket Escalation</h1>
    <form action="{{ route('settings.sla.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" id="sla_enabled" name="sla_enabled" value="1" {{ $enabled ? 'checked' : '' }}>
            <label class="form-check-label" for="sla_enabled">Enable SLA Escalation</label>
        </div>
        <div class="mb-3">
            <label for="sla_interval" class="form-label">Check Interval (minutes)</label>
            <input type="number" id="sla_interval" name="sla_interval" min="1" max="60" class="form-control" value="{{ $interval }}">
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
