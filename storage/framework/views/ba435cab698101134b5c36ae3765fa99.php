<?php $__env->startSection('content'); ?>
<div class="container text-center">
    <img src="<?php echo e(asset('assets/images/CCS.jpg')); ?>" alt="CCS Logo" class="img-fluid mb-4" style="max-width:200px;">
    <h1 class="mb-3">Welcome to the LCCD Integrated Information System</h1>
    <p class="lead mb-4">Empowering Christ-centered digital transformation for La Consolacion College Daet—where technology, transparency, and service unite.</p>
    <a href="<?php echo e(route('login')); ?>" class="btn cta btn-lg">Login</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ian\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/landing.blade.php ENDPATH**/ ?>