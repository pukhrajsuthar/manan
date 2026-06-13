<?php $__env->startSection('title', 'Items'); ?>
<?php $__env->startSection('content_header'); ?>
    <h1>Items / Products <a href="<?php echo e(route('admin.items.create')); ?>" class="btn btn-primary btn-sm ml-2">+ New</a></h1>
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
            <thead><tr><th>Name</th><th>HSN</th><th>Unit</th><th>Price</th><th>Tax Rule</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($item->name); ?></td>
                    <td><?php echo e($item->hsn_code ?? '—'); ?></td>
                    <td><?php echo e($item->unit); ?></td>
                    <td>₹<?php echo e(number_format($item->selling_price, 2)); ?></td>
                    <td><?php echo e($item->taxRule->name ?? '—'); ?></td>
                    <td><?php echo e($item->category ?? '—'); ?></td>
                    <td><span class="badge badge-<?php echo e($item->is_active ? 'success' : 'secondary'); ?>"><?php echo e($item->is_active ? 'Active' : 'Inactive'); ?></span></td>
                    <td>
                        <a href="<?php echo e(route('admin.items.edit', $item)); ?>" class="btn btn-xs btn-warning">Edit</a>
                        <form action="<?php echo e(route('admin.items.destroy', $item)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-muted">No items found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer"><?php echo e($items->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/items/index.blade.php ENDPATH**/ ?>