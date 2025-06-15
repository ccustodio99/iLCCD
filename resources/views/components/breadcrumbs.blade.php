@props(['links' => []])
@if(count($links))
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb">
            @foreach($links as $link)
                @if(isset($link['url']) && $link['url'])
                    <li class="breadcrumb-item"><a href="{{ $link['url'] }}">{{ $link['label'] }}</a></li>
                @else
                    <li class="breadcrumb-item active" aria-current="page">{{ $link['label'] }}</li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
