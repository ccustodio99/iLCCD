<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex flex-column flex-lg-row min-vh-100 overflow-hidden">
    <div class="position-relative flex-fill d-none d-lg-block bg-dark">
        <div class="position-absolute top-50 start-50 translate-middle bg-white shadow p-4" style="transform: rotate(-4deg); max-width: 480px;">
            <img src="<?php echo e(asset('assets/images/CCS.jpg')); ?>" alt="CCS Illustration" class="img-fluid" />
        </div>
        <div class="position-absolute top-0 bottom-0 end-0" style="width: 120px; background-color: var(--color-primary); transform: skewX(-12deg); transform-origin: top right;"></div>
    </div>
    <div class="login-panel flex-grow-1 d-flex align-items-center justify-content-center p-4 position-relative" style="background-color: var(--color-primary);">
        <svg viewBox="0 0 200 200" class="position-absolute text-warning opacity-25" style="width:200px; height:200px; top:-60px; right:-60px;" xmlns="http://www.w3.org/2000/svg">
            <path fill="currentColor" d="M40.1,-49.5C54.7,-44.4,71.5,-37.7,73.5,-26.8C75.4,-16,62.5,-1.1,53,10.9C43.5,23,37.4,32.3,28.9,37.8C20.4,43.4,9.6,45.3,-1.5,47.2C-12.7,49.2,-25.4,51.2,-34.6,46.6C-43.9,42.1,-49.7,31.1,-52.8,20.2C-55.9,9.2,-56.3,-1.7,-55.1,-14.6C-53.9,-27.4,-51.1,-42.2,-42,-47.7C-32.9,-53.2,-16.4,-49.3,-1.6,-47.2C13.3,-45.1,26.6,-44.7,40.1,-49.5Z" transform="translate(100 100)" />
        </svg>
        <div class="card shadow w-100" style="max-width: 420px;">
            <div class="card-body">
                <div class="text-center mb-4">
                    <img src="<?php echo e(asset(setting('logo_path', 'assets/images/LCCD.jpg'))); ?>" alt="Logo" style="max-height:80px;" />
                </div>
                <h1 class="h4 text-center mb-1"><?php echo e(setting('home_heading')); ?></h1>
                <p class="lead text-center mb-4"><?php echo e(setting('home_tagline')); ?></p>
                <h2 class="h5 mb-3 text-center">Login to continue to app</h2>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input id="email" type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <a href="<?php echo e(route('password.request')); ?>" class="me-auto">Forgot Password?</a>
                        <button type="submit" class="btn cta">Login</button>
                    </div>
                    <div class="text-center">
                        Need an account? <a href="<?php echo e(route('register')); ?>">Sign up!</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/landing.blade.php ENDPATH**/ ?>