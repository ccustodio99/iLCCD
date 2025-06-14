<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'LCCD IIS'))</title>
    <link rel="icon" href="{{ asset(setting('favicon_path', 'favicon.ico')) }}">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: {{ setting('color_primary', '#1B2660') }};
            --color-accent: {{ setting('color_accent', '#FFCD38') }};
            --font-primary: '{{ setting('font_primary', 'Poppins') }}';
            --font-secondary: '{{ setting('font_secondary', 'Roboto') }}';
        }
        body {
            font-family: var(--font-primary), var(--font-secondary), 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .cta { background-color: var(--color-accent); color: var(--color-primary); }
    </style>
</head>
<body>
    <main id="main-content" class="py-5">
        @yield('content')
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite('resources/js/app.js')
    @endif
    @stack('scripts')
</body>
</html>
