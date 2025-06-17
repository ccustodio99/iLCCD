<div id="mainMenu" class="offcanvas offcanvas-start offcanvas-lg" tabindex="-1" role="navigation" aria-labelledby="mainMenuLabel" data-bs-scroll="true" data-bs-backdrop="false">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title text-center fw-bold mb-0 text-white" id="mainMenuLabel">
            <span id="sidebar-date">{{ \Carbon\Carbon::now(setting('timezone'))->format('M. d, y') }}</span><br>
            <span id="sidebar-time">{{ \Carbon\Carbon::now(setting('timezone'))->format('h:i:s A') }}</span>

        </h5>
        <button type="button" class="btn-close d-lg-none" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <nav class="sidebar" aria-label="Main navigation">
            <div class="navbar-brand d-flex flex-column align-items-center mb-3 text-white">
                <span id="sidebar-date-link" class="fw-semibold">{{ \Carbon\Carbon::now(setting('timezone'))->format('M. d, y') }}</span>
                <span id="sidebar-time-link">{{ \Carbon\Carbon::now(setting('timezone'))->format('h:i:s A') }}</span>
            </div>
            <ul class="nav flex-column">
                @auth
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('dashboard')) active @endif" href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">dashboard</span>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('tickets.*')) active @endif" href="{{ route('tickets.index') }}" @if(request()->routeIs('tickets.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">confirmation_number</span>Tickets</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('job-orders.*')) active @endif" href="{{ route('job-orders.index') }}" @if(request()->routeIs('job-orders.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">work</span>Job Orders</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('requisitions.*')) active @endif" href="{{ route('requisitions.index') }}" @if(request()->routeIs('requisitions.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">receipt_long</span>Requisitions</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('inventory.*')) active @endif" href="{{ route('inventory.index') }}" @if(request()->routeIs('inventory.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">inventory_2</span>Inventory</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('purchase-orders.*')) active @endif" href="{{ route('purchase-orders.index') }}" @if(request()->routeIs('purchase-orders.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">shopping_cart</span>Purchase Orders</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('documents.*')) active @endif" href="{{ route('documents.index') }}" @if(request()->routeIs('documents.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">folder</span>Documents</a></li>
                    <li class="nav-item">
                        <a class="nav-link @if(request()->routeIs('documents.dashboard')) active @endif" href="{{ route('documents.dashboard') }}">
                            <span class="material-symbols-outlined me-1" aria-hidden="true">monitoring</span>Document KPI
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('kpi.dashboard')) active @endif" href="{{ route('kpi.dashboard') }}" @if(request()->routeIs('kpi.dashboard')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">query_stats</span>KPI Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('audit-trails.*')) active @endif" href="{{ route('audit-trails.index') }}" @if(request()->routeIs('audit-trails.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">history</span>Audit Trail</a></li>
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item"><a class="nav-link @if(request()->routeIs('users.*')) active @endif" href="{{ route('users.index') }}" @if(request()->routeIs('users.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">group</span>Users</a></li>
                        <li class="nav-item">
                            <a class="nav-link @if(request()->routeIs('settings.*')) active @endif" href="{{ route('settings.index') }}" @if(request()->routeIs('settings.*')) aria-current="page" @endif>
                                <span class="material-symbols-outlined me-1" aria-hidden="true">settings</span>Settings
                            </a>
                        </li>
                    @endif
                    <li class="nav-item"><a class="nav-link @if(request()->routeIs('profile.*')) active @endif" href="{{ route('profile.edit') }}" @if(request()->routeIs('profile.*')) aria-current="page" @endif><span class="material-symbols-outlined me-1" aria-hidden="true">person</span>Profile</a></li>
                    <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link p-0"><span class="material-symbols-outlined me-1" aria-hidden="true">logout</span>Logout</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}"><span class="material-symbols-outlined me-1" aria-hidden="true">login</span>Login</a></li>
                @endauth
            </ul>
        </nav>
    </div>
</div>
@push('scripts')
<script>
const timeZone = @json(setting('timezone', config('app.timezone')));
const monthFormatter = new Intl.DateTimeFormat('en-US', { month: 'short', timeZone });
const dayFormatter = new Intl.DateTimeFormat('en-US', { day: '2-digit', timeZone });
const yearFormatter = new Intl.DateTimeFormat('en-US', { year: '2-digit', timeZone });
const timeFormatter = new Intl.DateTimeFormat('en-US', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: true,
    timeZone,
});

function updateSidebarDateTime() {
    const now = new Date();
    const date = `${monthFormatter.format(now)}. ${dayFormatter.format(now)}, ${yearFormatter.format(now)}`;
    const time = timeFormatter.format(now);
    document.querySelectorAll('#sidebar-date, #sidebar-date-link').forEach(el => el.textContent = date);
    document.querySelectorAll('#sidebar-time, #sidebar-time-link').forEach(el => el.textContent = time);
}
updateSidebarDateTime();
setInterval(updateSidebarDateTime, 1000);
</script>
@endpush
