<header role="banner" class="site-header">
    <div class="header-text">{!! nl2br(e(setting('header_text'))) !!}</div>
    <div class="notification-area">
        @yield('header-notifications')
    </div>
</header>
