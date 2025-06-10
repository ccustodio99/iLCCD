<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'LCCD IIS') }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', 'Montserrat', sans-serif; background-color: #f8f9fa; }
        .navbar { background-color: #1B2660; }
        .navbar-brand, .nav-link { color: #ffffff !important; }
        .cta { background-color: #FFCD38; color: #1B2660; }
        footer { background-color: #1B2660; color: #ffffff; padding: 1rem 0; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">

        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ asset('assets/images/LCCD.jpg') }}" alt="LCCD Logo" width="40" class="me-2">
            <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Department Logo" width="40" class="me-2">
            LCCD Integrated Information System
        </a>
    </div>
</nav>
<main class="py-5">
    @yield('content')
</main>
<footer class="text-center">
    &copy; {{ date('Y') }} La Consolacion College Daet
</footer>
</body>
</html>
