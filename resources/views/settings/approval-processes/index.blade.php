@extends('layouts.app')

@section('title', 'Approval Processes')

@section('content')
<div class="container">
    @include('components.breadcrumbs', ['links' => [
        ['label' => 'Settings', 'url' => route('settings.index')],
        ['label' => 'Approval Processes']
    ]])
    <h1 class="mb-4">Approval Processes</h1>
    @include('components.per-page-selector')
    <a href="{{ route('approval-processes.create') }}" class="btn btn-sm btn-primary mb-3">Add Process</a>
    @include('components.approval-process-table', ['processes' => $processes])
</div>
@endsection
