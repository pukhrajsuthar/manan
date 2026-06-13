<?php $__env->startSection('title', 'Tax Rules'); ?>
<?php $__env->startSection('content_header'); ?>
    <h1>Tax Rules <a href="<?php echo e(route('admin.tax-rules.create')); ?>" class="btn btn-primary btn-sm ml-2">+ New</a></h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php if(session('success')): ?>
    <?php if (isset($component)) { $__componentOriginal9d0273d6550ddf39dc9a547c96729fed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::resolve(['theme' => 'success','dismissable' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><?php echo e(session('success')); ?> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9d0273d6550ddf39dc9a547c96729fed)): ?>
<?php $attributes = $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed; ?>
<?php unset($__attributesOriginal9d0273d6550ddf39dc9a547c96729fed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9d0273d6550ddf39dc9a547c96729fed)): ?>
<?php $component = $__componentOriginal9d0273d6550ddf39dc9a547c96729fed; ?>
<?php unset($__componentOriginal9d0273d6550ddf39dc9a547c96729fed); ?>
<?php endif; ?>
<?php endif; ?>
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Name</th><th>Type</th><th>CGST %</th><th>SGST %</th><th>IGST %</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $taxRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($t->name); ?></td>
                    <td><span class="badge badge-info"><?php echo e(strtoupper($t->type)); ?></span></td>
                    <td><?php echo e($t->cgst_rate); ?>%</td>
                    <td><?php echo e($t->sgst_rate); ?>%</td>
                    <td><?php echo e($t->igst_rate); ?>%</td>
                    <td><?php echo e($t->description ?? '—'); ?></td>
                    <td><span class="badge badge-<?php echo e($t->is_active ? 'success' : 'secondary'); ?>"><?php echo e($t->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('admin.tax-rules.edit', $t)); ?>" class="btn btn-xs btn-warning">Edit</a>
                        <form action="<?php echo e(route('admin.tax-rules.destroy', $t)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-muted">No tax rules found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer"><?php echo e($taxRules->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/tax-rules/index.blade.php ENDPATH**/ ?>