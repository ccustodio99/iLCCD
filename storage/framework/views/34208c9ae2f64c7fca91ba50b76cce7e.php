<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 400px;">
    <h1 class="mb-4 text-center">Login</h1>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/auth/login.blade.php ENDPATH**/ ?>