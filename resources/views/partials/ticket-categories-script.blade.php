@php
    $categoryData = $categories->mapWithKeys(function ($cat) {
        return [
            $cat->id => $cat->children
                ->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])
                ->values()
        ];
    });
@endphp
<script>
    window.ticketCategories = @json($categoryData);
</script>
