<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e(config('app.name', 'LCCD IIS')); ?></title>
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
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(asset('assets/images/LCCD.jpg')); ?>" alt="LCCD Logo" width="40" class="me-2">
            <img src="<?php echo e(asset('assets/images/CCS.jpg')); ?>" alt="CCS Department Logo" width="40" class="me-2">
            LCCD Integrated Information System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu" aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
        <?php if(auth()->guard()->check()): ?>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('tickets.index')); ?>">Tickets</a></li>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('job-orders.index')); ?>">Job Orders</a></li>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('requisitions.index')); ?>">Requisitions</a></li>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('inventory.index')); ?>">Inventory</a></li>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('purchase-orders.index')); ?>">Purchase Orders</a></li>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('documents.index')); ?>">Documents</a></li>
            <?php if(auth()->user()->role === 'admin'): ?>
                <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('users.index')); ?>">Users</a></li>
            <?php endif; ?>
            <li class="nav-item me-lg-3"><a class="nav-link" href="<?php echo e(route('profile.edit')); ?>">Profile</a></li>
            <li class="nav-item">
                <form method="POST" action="<?php echo e(route('logout')); ?>">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="nav-link btn btn-link p-0">Logout</button>
                </form>
            </li>
        </ul>
        <?php else: ?>
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item"><a class="nav-link" href="<?php echo e(route('login')); ?>">Login</a></li>
        </ul>
        <?php endif; ?>
    </div>
</nav>
<main id="main-content" class="py-5">
    <?php echo $__env->yieldContent('content'); ?>
</main>
<footer class="text-center">
    &copy; <?php echo e(date('Y')); ?> La Consolacion College Daet
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php /**PATH C:\Users\ian\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/layouts/app.blade.php ENDPATH**/ ?>