@extends('layouts.app')

@section('title', 'Search')

@section('content')
<div class="container">
    <h1 class="mb-4">Search Results</h1>
    <form method="GET" action="{{ route('search.index') }}" class="mb-3 row g-2">
        <div class="col">
            <label for="query" class="visually-hidden">Search</label>
            <input id="query" type="search" name="query" value="{{ $query }}" class="form-control" placeholder="Search...">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    @if($query)
        @forelse($results as $module => $items)
            <h2 class="mt-4">{{ ucfirst(str_replace('_', ' ', $module)) }}</h2>
            @if($items->isEmpty())
                <p class="text-muted">No matches found.</p>
            @else
                <ul class="list-group mb-3">
                    @foreach($items as $item)
                        <li class="list-group-item">
                            @switch($module)
                                @case('tickets')
                                    {{ $item->subject }}
                                    @break
                                @case('jobOrders')
                                    {{ $item->description }}
                                    @break
                                @case('requisitions')
                                    {{ $item->purpose }}
                                    @break
                                @case('documents')
                                    {{ $item->title }}
                                    @break
                            @endswitch
                        </li>
                    @endforeach
                </ul>
            @endif
        @empty
            <p class="text-muted">No results found.</p>
        @endforelse
    @else
        <p class="text-muted">Enter a search term above.</p>
    @endif
</div>
@endsection
