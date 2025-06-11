<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'LCCD IIS'))</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', 'Montserrat', sans-serif; background-color: #f8f9fa; }
        .sidebar {
            width: 200px;
            min-height: 100vh;
            background-color: #1B2660;
            position: fixed;
            top: 0;
            left: 0;
            transition: left 0.3s ease;
        }
        #menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: #1B2660;
            font-size: 1.5rem;
            position: fixed;
            top: 10px;
            left: 10px;
            z-index: 1100;
        }
        .sidebar a {
            color: #ffffff;
            text-decoration: none;
            display: block;
            padding: 0.75rem 1rem;
        }
        .nav-link.active {
            background-color: #FFCD38;
            color: #1B2660;
            font-weight: 600;
        }
        .sidebar a:hover {
            background-color: #FFCD38;
            color: #1B2660;
        }
        .content-wrapper {
            margin-left: 200px;
            padding-left: 1rem;
        }

        @media (max-width: 768px) {
            .sidebar {
                left: -200px;
            }
            .sidebar.active {
                left: 0;
            }
            nav.sidebar.active + .content-wrapper {
                margin-left: 200px;
            }
            .content-wrapper {
                margin-left: 0;
            }
            #menu-toggle {
                display: block;
            }
        }
        .cta { background-color: #FFCD38; color: #1B2660; }
        footer { background-color: #1B2660; color: #ffffff; padding: 1rem 0; }
        #back-to-top {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            display: none;
            z-index: 1000;
        }
        .skip-link {
            position: absolute;
            top: -40px;
            left: 0;
            background: #1B2660;
            color: #ffffff;
            padding: 0.5rem;
            z-index: 100;
            transition: top 0.3s ease;
        }
        .skip-link:focus {
            top: 0;
        }
        .card-quick {
            border-radius: 1rem;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }
        .card-quick:hover {
            transform: translateY(-3px);
        }
        .card-quick .material-symbols-outlined {
            font-size: 2.5rem;
            color: #1B2660;
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <button id="menu-toggle" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>
    <div class="d-flex">
        <nav class="sidebar" aria-label="Main navigation">
            <a class="navbar-brand d-flex align-items-center mb-3" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/LCCD.jpg') }}" alt="LCCD Logo" width="40" class="me-2">
                <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Department Logo" width="40" class="me-2">
            </a>
            <ul class="nav flex-column">
                @auth
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('tickets.*')) active @endif" href="{{ route('tickets.index') }}" @if(request()->routeIs('tickets.*')) aria-current="page" @endif>Tickets</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('job-orders.*')) active @endif" href="{{ route('job-orders.index') }}" @if(request()->routeIs('job-orders.*')) aria-current="page" @endif>Job Orders</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('requisitions.*')) active @endif" href="{{ route('requisitions.index') }}" @if(request()->routeIs('requisitions.*')) aria-current="page" @endif>Requisitions</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('inventory.*')) active @endif" href="{{ route('inventory.index') }}" @if(request()->routeIs('inventory.*')) aria-current="page" @endif>Inventory</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('purchase-orders.*')) active @endif" href="{{ route('purchase-orders.index') }}" @if(request()->routeIs('purchase-orders.*')) aria-current="page" @endif>Purchase Orders</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('documents.*')) active @endif" href="{{ route('documents.index') }}" @if(request()->routeIs('documents.*')) aria-current="page" @endif>Documents</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('audit-trails.*')) active @endif" href="{{ route('audit-trails.index') }}" @if(request()->routeIs('audit-trails.*')) aria-current="page" @endif>Audit Trail</a></li>
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}" @if(request()->routeIs('users.*')) aria-current="page" @endif>Users</a></li>
                    @endif
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('profile.*')) active @endif" href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.*')) aria-current="page" @endif>Profile</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link p-0">Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                @endauth
            </ul>
        </nav>
        <div class="content-wrapper flex-grow-1">
            <main id="main-content" class="py-5">
                @yield('content')
            </main>
            <footer class="text-center">
                &copy; {{ date('Y') }} La Consolacion College Daet
            </footer>
        </div>
    </div>
<button id="back-to-top" class="btn btn-secondary" aria-label="Back to top">&uarr;</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const backToTop = document.getElementById('back-to-top');
    window.addEventListener('scroll', () => {
        backToTop.style.display = window.scrollY > 200 ? 'block' : 'none';
    });
    backToTop.addEventListener('click', () => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.querySelector('.sidebar');
    menuToggle.addEventListener('click', () => {
        const expanded = sidebar.classList.toggle('active');
        menuToggle.setAttribute('aria-expanded', expanded);
    });
</script>
</body>
</html>
