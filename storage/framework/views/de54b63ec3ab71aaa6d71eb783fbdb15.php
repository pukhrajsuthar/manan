<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content_header'); ?>
    <h1>Dashboard <small class="text-muted"><?php echo e($fy); ?></small></h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php if(session('success')): ?>
    <?php if (isset($component)) { $__componentOriginal9d0273d6550ddf39dc9a547c96729fed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::resolve(['theme' => 'success','title' => 'Success','dismissable' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
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

<div class="row">
    <div class="col-lg-3 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => ''.e(number_format($stats['total_invoices'])).'','text' => 'Total Invoices','icon' => 'fas fa-file-invoice','theme' => 'primary','url' => ''.e(route('admin.invoices.index')).'','urlText' => 'View all'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
    <div class="col-lg-3 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => '₹'.e(number_format($stats['total_revenue'], 2)).'','text' => 'Total Revenue','icon' => 'fas fa-rupee-sign','theme' => 'success'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
    <div class="col-lg-3 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => '₹'.e(number_format($stats['total_outstanding'], 2)).'','text' => 'Outstanding','icon' => 'fas fa-clock','theme' => 'warning'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
    <div class="col-lg-3 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => ''.e($stats['overdue_count']).'','text' => 'Overdue','icon' => 'fas fa-exclamation-circle','theme' => 'danger'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => ''.e($stats['draft_count']).'','text' => 'Draft','icon' => 'fas fa-pencil-alt','theme' => 'secondary','url' => ''.e(route('admin.invoices.index', ['status' => 'draft'])).'','urlText' => 'View'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
    <div class="col-lg-4 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => ''.e($stats['sent_count']).'','text' => 'Sent','icon' => 'fas fa-paper-plane','theme' => 'info','url' => ''.e(route('admin.invoices.index', ['status' => 'sent'])).'','urlText' => 'View'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
    <div class="col-lg-4 col-6">
        <?php if (isset($component)) { $__componentOriginal28a68399664384fcdb4ffafd23cbfe61 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61 = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::resolve(['title' => ''.e($stats['paid_count']).'','text' => 'Paid','icon' => 'fas fa-check-circle','theme' => 'success','url' => ''.e(route('admin.invoices.index', ['status' => 'paid'])).'','urlText' => 'View'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-small-box'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\SmallBox::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $attributes = $__attributesOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__attributesOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61)): ?>
<?php $component = $__componentOriginal28a68399664384fcdb4ffafd23cbfe61; ?>
<?php unset($__componentOriginal28a68399664384fcdb4ffafd23cbfe61); ?>
<?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Invoices</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-sm btn-primary">View All</a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Invoice #</th>
                            <th>Client</th>
                            <th>Company</th>
                            <th>Date</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recent_invoices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $invoice): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($invoice->invoice_number); ?></td>
                            <td><?php echo e($invoice->client->name ?? '—'); ?></td>
                            <td><?php echo e($invoice->company->name ?? '—'); ?></td>
                            <td><?php echo e($invoice->invoice_date?->format('d M Y')); ?></td>
                            <td>₹<?php echo e(number_format($invoice->grand_total, 2)); ?></td>
                            <td>
                                <?php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','cancelled'=>'danger']; ?>
                                <span class="badge badge-<?php echo e($colors[$invoice->status] ?? 'secondary'); ?>"><?php echo e(ucfirst($invoice->status)); ?></span>
                            </td>
                            <td><a href="<?php echo e(route('admin.invoices.show', $invoice)); ?>" class="btn btn-xs btn-default">View</a></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted">No invoices yet.</td></tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/dashboard.blade.php ENDPATH**/ ?>