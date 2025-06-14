<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['title' => null, 'showSidebar' => true]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['title' => null, 'showSidebar' => true]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>
<header role="banner" class="site-header navbar navbar-expand bg-white shadow-sm px-3">
    <?php if($showSidebar): ?>
    <button id="menu-toggle" class="btn btn-link me-2" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>
    <button id="breadcrumb-toggle" class="btn btn-link me-2" aria-label="Toggle breadcrumbs" aria-controls="breadcrumb-panel" aria-expanded="false">
        <span class="material-symbols-outlined" aria-hidden="true">view_headline</span>
        <span class="visually-hidden">Toggle breadcrumbs</span>
    </button>
    <?php endif; ?>
    <img src="<?php echo e(asset(setting('logo_path', 'assets/images/LCCD.jpg'))); ?>" alt="LCCD Logo" width="40" class="me-1">
    <img src="<?php echo e(asset('assets/images/CCS.jpg')); ?>" alt="CCS Department Logo" width="40" class="me-2">
    <span class="navbar-brand"><?php echo e($title ?? config('app.name')); ?></span>
    <form method="GET" action="<?php echo e(route('search.index')); ?>" class="d-flex ms-auto me-2" role="search">
        <label for="global-search" class="visually-hidden">Search</label>
        <input id="global-search" class="form-control" type="search" name="query" placeholder="Search...">
    </form>
    <a href="<?php echo e(route('help')); ?>" class="btn btn-link me-2" aria-label="Help">
        <span class="material-symbols-outlined" aria-hidden="true">help</span>
    </a>
    <button class="btn btn-link me-2" type="button" data-bs-toggle="modal" data-bs-target="#notificationsModal" aria-label="Notifications">
        <span class="material-symbols-outlined" aria-hidden="true">notifications</span>
    </button>
    <div class="dropdown">
        <button class="btn btn-link dropdown-toggle p-0" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false" aria-label="User menu">
            <img src="<?php echo e(Auth::user()->profile_photo_url ?? 'https://via.placeholder.com/40'); ?>" alt="<?php echo e(Auth::user()->name ?? 'User'); ?>" class="rounded-circle" width="40" height="40">
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
            <li><a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">Profile</a></li>
            <li><a class="dropdown-item" href="<?php echo e(route('settings.index')); ?>">Settings</a></li>
            <li><a class="dropdown-item" href="<?php echo e(route('help')); ?>">Help</a></li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="dropdown-item">Logout</button>
                </form>
            </li>
        </ul>
    </div>
</header>
<?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/components/site-header.blade.php ENDPATH**/ ?>