@extends('layouts.app')

@section('title', 'Localization Settings')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Localization']
    ]])
    <h1 class="mb-4">Localization Settings</h1>
    <form action="{{ route('settings.localization.update') }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="timezone" class="form-label">Timezone</label>
            <select id="timezone" name="timezone" class="form-select">
                @foreach($timezones as $tz)
                    <option value="{{ $tz }}" {{ $timezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label d-block">Date Format</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="format_ymd" name="date_format" value="Y-m-d" {{ $date_format === 'Y-m-d' ? 'checked' : '' }}>
                <label class="form-check-label" for="format_ymd">YYYY-MM-DD</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" id="format_dmy" name="date_format" value="d/m/Y" {{ $date_format === 'd/m/Y' ? 'checked' : '' }}>
                <label class="form-check-label" for="format_dmy">DD/MM/YYYY</label>
            </div>
        </div>
        <button type="submit" class="btn btn-primary me-2">Save</button>
        <a href="{{ route('settings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
