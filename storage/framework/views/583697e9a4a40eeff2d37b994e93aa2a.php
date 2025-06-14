<?php if (isset($component)) { $__componentOriginalfdc8967a87956c0a7185abbef03fae20 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalfdc8967a87956c0a7185abbef03fae20 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.site-header','data' => ['title' => setting('header_text'),'showSidebar' => $showSidebar ?? true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('site-header'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['title' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(setting('header_text')),'show-sidebar' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($showSidebar ?? true)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalfdc8967a87956c0a7185abbef03fae20)): ?>
<?php $attributes = $__attributesOriginalfdc8967a87956c0a7185abbef03fae20; ?>
<?php unset($__attributesOriginalfdc8967a87956c0a7185abbef03fae20); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalfdc8967a87956c0a7185abbef03fae20)): ?>
<?php $component = $__componentOriginalfdc8967a87956c0a7185abbef03fae20; ?>
<?php unset($__componentOriginalfdc8967a87956c0a7185abbef03fae20); ?>
<?php endif; ?>
<?php /**PATH D:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/layouts/header.blade.php ENDPATH**/ ?>