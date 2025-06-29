@props(['title' => null, 'showSidebar' => true])
<header role="banner" class="site-header navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid align-items-center">
        @if($showSidebar)
        <button id="menu-toggle" class="btn btn-link me-2 d-lg-none" aria-label="Toggle menu" aria-controls="mainMenu" aria-expanded="false">&#9776;</button>
        @endif
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center text-decoration-none me-2">
            <img src="{{ asset(setting('logo_path', 'assets/images/LCCD.jpg')) }}" alt="LCCD Logo" width="40" class="me-1">
            <img src="{{ asset('assets/images/CCS.jpg') }}" alt="CCS Department Logo" width="40" class="me-2">
        </a>
        <div class="d-flex flex-column">
            <h1 class="h6 mb-0 my-auto header-title">{!! nl2br(e(setting('header_text'))) !!}</h1>
            @include('components.breadcrumbs', ['links' => $breadcrumbs])
        </div>
        <nav class="d-flex align-items-center ms-auto" aria-label="Global">
            <form method="GET" action="{{ route('search.index') }}" class="d-flex me-2" role="search">
                <label for="global-search" class="visually-hidden">Search</label>
                <input id="global-search" class="form-control" type="search" name="query" placeholder="Search..." title="Search site">
            </form>
            <a href="{{ route('help') }}" class="btn btn-link me-2" aria-label="Help">
                <span class="material-symbols-outlined" aria-hidden="true">help</span>
            </a>
            <button class="btn btn-link me-2" type="button" data-bs-toggle="modal" data-bs-target="#notificationsModal" aria-label="Notifications">
                <span class="material-symbols-outlined" aria-hidden="true">notifications</span>
            </button>
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle p-0" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://via.placeholder.com/40' }}" alt="{{ Auth::user()->name ?? 'User' }}" class="rounded-circle" width="40" height="40">
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                    @if(auth()->user()->role === 'admin')
                        <li><a class="dropdown-item" href="{{ route('settings.index') }}">Settings</a></li>
                    @endif
                    <li><a class="dropdown-item" href="{{ route('help') }}">Help</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
