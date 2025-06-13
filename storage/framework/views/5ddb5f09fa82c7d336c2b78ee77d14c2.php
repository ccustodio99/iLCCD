<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'LCCD IIS')); ?></title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&family=Roboto:wght@400;700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    <!-- Material Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --color-primary: <?php echo e(setting('color_primary', '#1B2660')); ?>;
            --color-accent: <?php echo e(setting('color_accent', '#FFCD38')); ?>;
        }
        body { font-family: '<?php echo e(setting('font_primary', 'Poppins')); ?>', '<?php echo e(setting('font_secondary', 'Roboto')); ?>', 'Montserrat', sans-serif; background-color: #f8f9fa; }
        .sidebar {
            width: 200px;
            min-height: 100vh;
            background-color: var(--color-primary);
            position: fixed;
            top: 0;
            left: 0;
            transition: left 0.3s ease;
        }
        #menu-toggle {
            display: none;
            background: transparent;
            border: none;
            color: var(--color-primary);
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
            background-color: var(--color-accent);
            color: var(--color-primary);
            font-weight: 600;
        }
        .sidebar a:hover {
            background-color: var(--color-accent);
            color: var(--color-primary);
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
        .cta { background-color: var(--color-accent); color: var(--color-primary); }
        footer { background-color: var(--color-primary); color: #ffffff; padding: 1rem 0; }
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
            background: var(--color-primary);
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
            color: var(--color-primary);
        }
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <button id="menu-toggle" aria-label="Toggle menu" aria-expanded="false">&#9776;</button>
    <div class="d-flex">
        <nav class="sidebar" aria-label="Main navigation">
            <a class="navbar-brand d-flex align-items-center mb-3" href="<?php echo e(route('home')); ?>">
                <img src="<?php echo e(asset('assets/images/LCCD.jpg')); ?>" alt="LCCD Logo" width="40" class="me-2">
                <img src="<?php echo e(asset('assets/images/CCS.jpg')); ?>" alt="CCS Department Logo" width="40" class="me-2">
            </a>
            <ul class="nav flex-column">
                <?php if(auth()->guard()->check()): ?>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('dashboard')): ?> active <?php endif; ?>" href="<?php echo e(route('dashboard')); ?>" <?php if(request()->routeIs('dashboard')): ?> aria-current="page" <?php endif; ?>>Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('tickets.*')): ?> active <?php endif; ?>" href="<?php echo e(route('tickets.index')); ?>" <?php if(request()->routeIs('tickets.*')): ?> aria-current="page" <?php endif; ?>>Tickets</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('job-orders.*')): ?> active <?php endif; ?>" href="<?php echo e(route('job-orders.index')); ?>" <?php if(request()->routeIs('job-orders.*')): ?> aria-current="page" <?php endif; ?>>Job Orders</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('requisitions.*')): ?> active <?php endif; ?>" href="<?php echo e(route('requisitions.index')); ?>" <?php if(request()->routeIs('requisitions.*')): ?> aria-current="page" <?php endif; ?>>Requisitions</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('inventory.*')): ?> active <?php endif; ?>" href="<?php echo e(route('inventory.index')); ?>" <?php if(request()->routeIs('inventory.*')): ?> aria-current="page" <?php endif; ?>>Inventory</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('purchase-orders.*')): ?> active <?php endif; ?>" href="<?php echo e(route('purchase-orders.index')); ?>" <?php if(request()->routeIs('purchase-orders.*')): ?> aria-current="page" <?php endif; ?>>Purchase Orders</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('documents.*')): ?> active <?php endif; ?>" href="<?php echo e(route('documents.index')); ?>" <?php if(request()->routeIs('documents.*')): ?> aria-current="page" <?php endif; ?>>Documents</a></li>
                    <li class="nav-item">
                        <a class="nav-link <?php if(request()->routeIs('documents.dashboard')): ?> active <?php endif; ?>" href="<?php echo e(route('documents.dashboard')); ?>">
                            Document KPI
                        </a>
                    </li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('kpi.dashboard')): ?> active <?php endif; ?>" href="<?php echo e(route('kpi.dashboard')); ?>" <?php if(request()->routeIs('kpi.dashboard')): ?> aria-current="page" <?php endif; ?>>KPI Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('audit-trails.*')): ?> active <?php endif; ?>" href="<?php echo e(route('audit-trails.index')); ?>" <?php if(request()->routeIs('audit-trails.*')): ?> aria-current="page" <?php endif; ?>>Audit Trail</a></li>
                    <?php if(auth()->user()->role === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('users.*')): ?> active <?php endif; ?>" href="<?php echo e(route('users.index')); ?>" <?php if(request()->routeIs('users.*')): ?> aria-current="page" <?php endif; ?>>Users</a></li>
                        <li class="nav-item">
                            <a class="nav-link <?php if(request()->routeIs('settings.*')): ?> active <?php endif; ?>" href="<?php echo e(route('settings.index')); ?>" data-bs-toggle="modal" data-bs-target="#settingsModal" <?php if(request()->routeIs('settings.*')): ?> aria-current="page" <?php endif; ?>>
                                Settings
                            </a>
                        </li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link <?php if(request()->routeIs('profile.*')): ?> active <?php endif; ?>" href="<?php echo e(route('profile.edit')); ?>" <?php if(request()->routeIs('profile.*')): ?> aria-current="page" <?php endif; ?>>Profile</a></li>
                    <li class="nav-item">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="nav-link btn btn-link p-0">Logout</button>
                        </form>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a></li>
                <?php endif; ?>
            </ul>
        </nav>
        <div class="content-wrapper flex-grow-1">
            <main id="main-content" class="py-5">
                <?php echo $__env->yieldContent('content'); ?>
            </main>
            <footer class="text-center">
                &copy; <?php echo e(date('Y')); ?> La Consolacion College Daet CMS
            </footer>
        </div>
    </div>
    <?php echo $__env->make('settings.partials.settings-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<button id="back-to-top" class="btn btn-secondary" aria-label="Back to top">&uarr;</button>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/app.js'); ?>
<?php endif; ?>
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
<?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/layouts/app.blade.php ENDPATH**/ ?>