<?php
    $manifestPath = public_path('build/manifest.json');
    $includeScript = false;
    if (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        $includeScript = isset($manifest['resources/js/category-collapse.js']);
    }
?>
<?php if($includeScript || file_exists(public_path('hot'))): ?>
    <?php echo app('Illuminate\Foundation\Vite')('resources/js/category-collapse.js'); ?>
<?php endif; ?>
<?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/partials/category-collapse-script.blade.php ENDPATH**/ ?>