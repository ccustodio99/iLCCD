<?php $__env->startSection('title', 'System Settings'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <?php echo $__env->make('components.breadcrumbs', ['links' => [
        ['label' => 'Settings']
    ]], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    <h1 class="mb-2">System Settings</h1>
    <p class="text-muted mb-4">Administrators manage system defaults and theme settings here.</p>

    <div class="accordion" id="settingsAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCategories">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategories" aria-expanded="true" aria-controls="collapseCategories">
                    Categories
                </button>
            </h2>
            <div id="collapseCategories" class="accordion-collapse collapse show" aria-labelledby="headingCategories" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('ticket-categories.index'),'icon' => 'category','label' => 'Ticket Categories']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('ticket-categories.index')),'icon' => 'category','label' => 'Ticket Categories']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('job-order-types.index'),'icon' => 'work','label' => 'Job Order Types']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('job-order-types.index')),'icon' => 'work','label' => 'Job Order Types']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('inventory-categories.index'),'icon' => 'inventory_2','label' => 'Inventory Categories']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('inventory-categories.index')),'icon' => 'inventory_2','label' => 'Inventory Categories']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('document-categories.index'),'icon' => 'folder','label' => 'Document Categories']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('document-categories.index')),'icon' => 'folder','label' => 'Document Categories']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingGeneral">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseGeneral" aria-expanded="false" aria-controls="collapseGeneral">
                    General
                </button>
            </h2>
            <div id="collapseGeneral" class="accordion-collapse collapse" aria-labelledby="headingGeneral" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('settings.theme'),'icon' => 'color_lens','label' => 'Appearance']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.theme')),'icon' => 'color_lens','label' => 'Appearance']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('settings.localization'),'icon' => 'schedule','label' => 'Localization']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.localization')),'icon' => 'schedule','label' => 'Localization']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('settings.sla'),'icon' => 'priority_high','label' => 'Ticket Escalation']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.sla')),'icon' => 'priority_high','label' => 'Ticket Escalation']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingWorkflow">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseWorkflow" aria-expanded="false" aria-controls="collapseWorkflow">
                    Workflow
                </button>
            </h2>
            <div id="collapseWorkflow" class="accordion-collapse collapse" aria-labelledby="headingWorkflow" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('approval-processes.index'),'icon' => 'account_tree','label' => 'Approval Processes']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('approval-processes.index')),'icon' => 'account_tree','label' => 'Approval Processes']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="accordion-item">
            <h2 class="accordion-header" id="headingCommunication">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCommunication" aria-expanded="false" aria-controls="collapseCommunication">
                    Communication
                </button>
            </h2>
            <div id="collapseCommunication" class="accordion-collapse collapse" aria-labelledby="headingCommunication" data-bs-parent="#settingsAccordion">
                <div class="accordion-body">
                    <div class="row row-cols-1 row-cols-md-2 g-3">
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('announcements.index'),'icon' => 'campaign','label' => 'Announcements']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('announcements.index')),'icon' => 'campaign','label' => 'Announcements']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('settings.notifications'),'icon' => 'notifications','label' => 'Notifications']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.notifications')),'icon' => 'notifications','label' => 'Notifications']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                        <div class="col">
                            <?php if (isset($component)) { $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.settings-link','data' => ['href' => route('settings.email'),'icon' => 'mail','label' => 'Email']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('settings-link'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(route('settings.email')),'icon' => 'mail','label' => 'Email']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $attributes = $__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__attributesOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d)): ?>
<?php $component = $__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d; ?>
<?php unset($__componentOriginala5b6f3c1f2c2f072f32d90f107fd361d); ?>
<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\SynologyDrive\MIT Studies\xampp\htdocs\iLCCD\resources\views/settings/index.blade.php ENDPATH**/ ?>