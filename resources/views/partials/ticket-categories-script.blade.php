@php
    $categoryData = $categories->mapWithKeys(function ($cat) {
        return [$cat->id => $cat->children->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])];
    });
@endphp
<script>
    window.ticketCategories = @json($categoryData);
</script>
