<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', config('app.name', 'LCCD IIS')); ?></title>
    <link rel="icon" href="<?php echo e(asset(setting('favicon_path', 'favicon.ico'))); ?>">
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
            --font-primary: '<?php echo e(setting('font_primary', 'Poppins')); ?>';
            --font-secondary: '<?php echo e(setting('font_secondary', 'Roboto')); ?>';
        }
        body {
            font-family: var(--font-primary), var(--font-secondary), 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .cta { background-color: var(--color-accent); color: var(--color-primary); }
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
    </style>
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>
    <main id="main-content" class="py-5">
        <?php echo $__env->yieldContent('content'); ?>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <?php if(file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot'))): ?>
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php endif; ?>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/layouts/guest.blade.php ENDPATH**/ ?>