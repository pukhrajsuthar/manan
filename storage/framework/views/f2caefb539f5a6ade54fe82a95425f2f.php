<?php $__env->startSection('title', 'New Item'); ?>
<?php $__env->startSection('content_header'); ?><h1>New Item</h1><?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card" style="max-width:700px">
    <form action="<?php echo e(route('admin.items.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="card-body">
        <?php if($errors->any()): ?><?php if (isset($component)) { $__componentOriginal9d0273d6550ddf39dc9a547c96729fed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::resolve(['theme' => 'danger','dismissable' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?><ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul> <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9d0273d6550ddf39dc9a547c96729fed)): ?>
<?php $attributes = $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed; ?>
<?php unset($__attributesOriginal9d0273d6550ddf39dc9a547c96729fed); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9d0273d6550ddf39dc9a547c96729fed)): ?>
<?php $component = $__componentOriginal9d0273d6550ddf39dc9a547c96729fed; ?>
<?php unset($__componentOriginal9d0273d6550ddf39dc9a547c96729fed); ?>
<?php endif; ?><?php endif; ?>
        <div class="form-group"><label>Name *</label><input name="name" class="form-control" value="<?php echo e(old('name')); ?>" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="2"><?php echo e(old('description')); ?></textarea></div>
        <div class="row">
            <div class="col-md-4"><div class="form-group"><label>HSN Code</label><input name="hsn_code" class="form-control" value="<?php echo e(old('hsn_code')); ?>"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Unit *</label>
                <select name="unit" class="form-control" required>
                    <?php $__currentLoopData = ['Nos','Pcs','Kg','Litre','Metre','Box','Set','Pair','Sqft']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u); ?>" <?php echo e(old('unit') === $u ? 'selected' : ''); ?>><?php echo e($u); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div></div>
            <div class="col-md-4"><div class="form-group"><label>Category</label><input name="category" class="form-control" value="<?php echo e(old('category')); ?>"></div></div>
        </div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Selling Price (₹) *</label><input name="selling_price" type="number" step="0.01" class="form-control" value="<?php echo e(old('selling_price')); ?>" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Tax Rule *</label>
                <select name="tax_rule_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    <?php $__currentLoopData = $taxRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t->id); ?>" <?php echo e(old('tax_rule_id') == $t->id ? 'selected' : ''); ?>><?php echo e($t->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div></div>
        </div>
        <div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked><label class="custom-control-label" for="is_active">Active</label></div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Item</button>
        <a href="<?php echo e(route('admin.items.index')); ?>" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/items/create.blade.php ENDPATH**/ ?>