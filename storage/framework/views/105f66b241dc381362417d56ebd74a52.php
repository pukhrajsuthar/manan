<?php $__env->startSection('title', 'Invoices'); ?>
<?php $__env->startSection('content_header'); ?>
<div class="d-flex justify-content-between align-items-center">
    <h1>Invoices</h1>
    <a href="<?php echo e(route('admin.invoices.create')); ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Invoice
    </a>
</div>
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
    <div class="card-header">
        <form method="GET" class="form-inline">
            <input name="search" class="form-control form-control-sm mr-2" placeholder="Invoice #" value="<?php echo e(request('search')); ?>">
            <select name="status" class="form-control form-control-sm mr-2">
                <option value="">All Status</option>
                <?php $__currentLoopData = ['draft','sent','paid','cancelled']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <select name="payment_status" class="form-control form-control-sm mr-2">
                <option value="">All Payment</option>
                <?php $__currentLoopData = ['unpaid','partial','paid']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($s); ?>" <?php echo e(request('payment_status') === $s ? 'selected' : ''); ?>><?php echo e(ucfirst($s)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <button class="btn btn-sm btn-primary mr-2">Filter</button>
            <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-sm btn-default">Clear</a>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Invoice #</th><th>Date</th><th>Client</th><th>Company</th><th>Amount</th><th>Status</th><th>Payment</th><th>Actions</th></tr></thead>
            <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','cancelled'=>'danger']; ?>
                <tr>
                    <td><?php echo e($inv->invoice_number); ?></td>
                    <td><?php echo e($inv->invoice_date?->format('d M Y')); ?></td>
                    <td><?php echo e($inv->client->name ?? '—'); ?></td>
                    <td><?php echo e($inv->company->name ?? '—'); ?></td>
                    <td>₹<?php echo e(number_format($inv->grand_total, 2)); ?></td>
                    <td><span class="badge badge-<?php echo e($colors[$inv->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($inv->status)); ?></span></td>
                    <td>
                        <span class="badge badge-<?php echo e($inv->payment_status === 'paid' ? 'success' : ($inv->payment_status === 'partial' ? 'warning' : 'secondary')); ?>">
                            <?php echo e(ucfirst($inv->payment_status)); ?>

                        </span>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.invoices.show', $inv)); ?>" class="btn btn-xs btn-default">View</a>
                        <?php if($inv->status === 'draft'): ?>
                            <form action="<?php echo e(route('admin.invoices.send', $inv)); ?>" method="POST" class="d-inline">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-xs btn-info">Send</button>
                            </form>
                        <?php endif; ?>
                        <?php if(!in_array($inv->status, ['paid','cancelled'])): ?>
                            <form action="<?php echo e(route('admin.invoices.cancel', $inv)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Cancel invoice?')">
                                <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                                <button class="btn btn-xs btn-warning">Cancel</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr><td colspan="8" class="text-center text-muted">No invoices found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="card-footer"><?php echo e($invoices->links()); ?></div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/invoices/index.blade.php ENDPATH**/ ?>