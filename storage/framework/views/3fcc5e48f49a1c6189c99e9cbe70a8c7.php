<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="max-width: 500px;">
    <h1 class="mb-4">My Profile</h1>
    <div class="text-center mb-3">
        <img src="<?php echo e($user->profile_photo_url); ?>" alt="Profile Photo" class="rounded-circle" width="150">
    </div>
    <form action="<?php echo e(route('profile.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div class="mb-3">
            <label for="name" class="form-label">Name</label>
            <input id="name" type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input id="email" type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
        </div>
        <div class="mb-3">
            <label for="contact_info" class="form-label">Contact Information</label>
            <input id="contact_info" type="text" name="contact_info" class="form-control" value="<?php echo e(old('contact_info', $user->contact_info)); ?>">
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">New Password</label>
            <input id="password" type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control">
        </div>
        <button type="submit" class="btn cta me-2">Update</button>
        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/profile/edit.blade.php ENDPATH**/ ?>