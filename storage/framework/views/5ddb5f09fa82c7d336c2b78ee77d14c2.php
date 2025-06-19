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
            --header-height: 56px;
        }
        body {
            font-family: var(--font-primary), var(--font-secondary), 'Montserrat', sans-serif;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 200px;
            min-height: 100vh;
            background-color: var(--color-primary);
            position: fixed;
            top: 0;
            left: 0;
            transition: left 0.3s ease;
        }
        #mainMenu.offcanvas {
            width: 200px;
        }
        #menu-toggle {
            display: block;
            background: transparent;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            margin-right: 0.5rem;
        }
        #menu-toggle:hover {
            color: var(--color-accent);
        }

        @media (min-width: 992px) {
            #menu-toggle {
                display: none;
            }
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
            padding-left: 1rem;
            padding-bottom: 5rem;
        }

        @media (min-width: 992px) {
            header[role="banner"],
            .content-wrapper {
                margin-left: 200px;
            }
            #mainMenu {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: none !important;
            }
        }

        @media (max-width: 992px) {
            /* Let Bootstrap's offcanvas control the menu position */
            .sidebar {
                left: 0;
            }
            .content-wrapper {
                margin-left: 0;
            }
            header[role="banner"] {
                margin-left: 0;
            }
            #mainMenu.offcanvas {
                top: var(--header-height);
                height: calc(100vh - var(--header-height));
            }
        }
        .cta { background-color: var(--color-accent); color: var(--color-primary); }
        footer {
            background-color: var(--color-primary);
            color: #ffffff;
            padding: 1rem 0;
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
        }
        #toggle-footer {
            position: fixed;
            bottom: 4rem;
            right: 1rem;
            z-index: 1000;
        }
        #back-to-top {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            display: none;
            z-index: 1000;
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
        header[role="banner"] {
            background-color: var(--color-primary);
            color: #ffffff;
            border-bottom: 4px solid var(--color-accent);
            font-family: var(--font-primary), var(--font-secondary), sans-serif;
            padding: 0.5rem 1rem;
            position: sticky;
            top: 0;
            z-index: 1090;
        }
        .modal-dialog {
            margin-top: 5rem;
        }
        header[role="banner"] .notification-area {
            color: var(--color-accent);
        }
        .header-title {

            font-size: 1.25rem;
            font-weight: 700;
            color: var(--color-accent);
            text-shadow: 1px 1px 2px rgba(0,0,0,0.2);

        }
    </style>
</head>
<body>
    <?php echo $__env->make('components.hamburger-menu', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php if(auth()->guard()->check()): ?>
        <?php echo $__env->make('components.site-header', ['showSidebar' => true], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php else: ?>
        <?php echo $__env->make('layouts.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <?php endif; ?>
    <div class="d-flex">
        <div class="content-wrapper flex-grow-1">
            <main id="main-content" class="py-5">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <div id="flash-container"></div>
                <?php echo $__env->yieldContent('content'); ?>
            </main>
            <footer id="app-footer" class="text-center" style="<?php echo e(setting('show_footer', true) ? '' : 'display:none;'); ?>">

                <div class="mb-1"><?php echo nl2br(e(str_replace('{year}', date('Y'), setting('footer_text')))); ?></div>

            </footer>
            <button id="toggle-footer" class="btn btn-secondary" aria-label="Toggle footer">
                <?php echo e(setting('show_footer', true) ? 'Hide Footer' : 'Show Footer'); ?>

            </button>
        </div>
    </div>
    <?php echo $__env->make('partials.notifications-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
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
    const mainMenuEl = document.getElementById('mainMenu');
    if (menuToggle && mainMenuEl) {
        const offcanvasMenu = new bootstrap.Offcanvas(mainMenuEl, { backdrop: false, scroll: true });
        if (window.innerWidth >= 992) {
            offcanvasMenu.show();
        } else {
            offcanvasMenu.hide();
        }
        const toggleOffcanvas = () => {
            if (window.innerWidth < 992) {
                offcanvasMenu.toggle();
                const expanded = menuToggle.getAttribute('aria-expanded') === 'true';
                menuToggle.setAttribute('aria-expanded', (!expanded).toString());
            }
        };
       menuToggle.addEventListener('click', toggleOffcanvas);
        mainMenuEl.addEventListener('hide.bs.offcanvas', (event) => {
            if (window.innerWidth >= 992) {
                event.preventDefault();
            }
        });
        mainMenuEl.addEventListener('hidden.bs.offcanvas', () => {
            if (window.innerWidth < 992) {
                menuToggle.setAttribute('aria-expanded', 'false');
                menuToggle.focus();
            }
        });
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                offcanvasMenu.show();
            } else {
                offcanvasMenu.hide();
            }
        });
    }
    const toggleFooterBtn = document.getElementById('toggle-footer');
    const footer = document.getElementById('app-footer');
    toggleFooterBtn.addEventListener('click', () => {
        const visible = footer.style.display !== 'none';
        footer.style.display = visible ? 'none' : 'block';
        toggleFooterBtn.textContent = visible ? 'Show Footer' : 'Hide Footer';
    });
</script>
<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/layouts/app.blade.php ENDPATH**/ ?>