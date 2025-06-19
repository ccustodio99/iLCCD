<?php ($hideHeader = true); ?>


<?php $__env->startSection('title', 'Welcome'); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .hero-left {
        background: linear-gradient(135deg, var(--color-primary), var(--color-accent));
        color: #ffffff;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="container-fluid">
    <div class="row g-0 min-vh-100">
        <div class="col-md-6 d-flex flex-column justify-content-center align-items-center text-center p-5 hero-left">
            <img src="<?php echo e(asset('assets/images/LCCD.jpg')); ?>" alt="LCCD Logo" class="img-fluid mb-4" style="max-width:200px;">
            <h1 class="display-5 fw-bold mb-3"><?php echo e(setting('home_heading', 'Welcome to the LCCD Integrated Information System')); ?></h1>
            <p class="lead"><?php echo e(setting('home_tagline', 'Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.')); ?></p>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center p-5">
            <div class="card shadow-sm w-100" style="max-width: 400px;">
                <div class="card-body">
                    <h2 class="mb-4 text-center">Login</h2>
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
                        <div class="d-grid">
                            <button type="submit" class="btn cta">Login</button>
                        </div>
                        <div class="mt-3">
                            <a href="<?php echo e(route('password.request')); ?>">Forgot Your Password?</a>
                        </div>
                        <div class="mt-2">
                            <a href="<?php echo e(route('register')); ?>">Create an Account</a>
                        </div>
                        <div class="mt-2">
                            <a href="<?php echo e(route('home')); ?>">Back to Home</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/landing.blade.php ENDPATH**/ ?>