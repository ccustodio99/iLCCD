@props(['href', 'icon', 'label'])
<a href="{{ $href }}" class="card card-quick text-center text-decoration-none p-4 h-100" aria-label="{{ $label }}">
    <span class="material-symbols-outlined d-block mb-2" aria-hidden="true">{{ $icon }}</span>
    <span class="fw-semibold">{{ $label }}</span>
</a>
