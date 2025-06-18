@php
    $manifestPath = public_path('build/manifest.json');
    $includeScript = false;
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $includeScript = isset($manifest['resources/js/approval-stages.js']);
    }
@endphp
@if ($includeScript || file_exists(public_path('hot')))
    @vite('resources/js/approval-stages.js')
@endif
