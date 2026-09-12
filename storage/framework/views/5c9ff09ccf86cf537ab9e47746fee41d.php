<?php $__env->startSection('title', 'Edit Company'); ?>
<?php $__env->startSection('content_header'); ?>
    <h1>Edit Company: <?php echo e($company->name); ?></h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="card">
    <form action="<?php echo e(route('admin.companies.update', $company)); ?>" method="POST">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <div class="card-body">
        <?php if($errors->any()): ?>
            <?php if (isset($component)) { $__componentOriginal9d0273d6550ddf39dc9a547c96729fed = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9d0273d6550ddf39dc9a547c96729fed = $attributes; } ?>
<?php $component = JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::resolve(['theme' => 'danger','dismissable' => true] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('adminlte-alert'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\JeroenNoten\LaravelAdminLte\View\Components\Widget\Alert::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                <ul class="mb-0"><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($e); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?></ul>
             <?php echo $__env->renderComponent(); ?>
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
            <div class="col-md-6">
                <div class="form-group"><label>Company Name *</label><input name="name" class="form-control" value="<?php echo e(old('name', $company->name)); ?>" required></div>
                <div class="form-group"><label>GSTIN</label><input name="gstin" class="form-control" value="<?php echo e(old('gstin', $company->gstin)); ?>"></div>
                <div class="form-group"><label>PAN</label><input name="pan" class="form-control" value="<?php echo e(old('pan', $company->pan)); ?>"></div>
                <div class="form-group"><label>Email</label><input name="email" type="email" class="form-control" value="<?php echo e(old('email', $company->email)); ?>"></div>
                <div class="form-group"><label>Phone</label><input name="phone" class="form-control" value="<?php echo e(old('phone', $company->phone)); ?>"></div>
                <div class="form-group"><label>Website</label><input name="website" class="form-control" value="<?php echo e(old('website', $company->website)); ?>"></div>
            </div>
            <div class="col-md-6">
                <div class="form-group"><label>Address *</label><textarea name="address" class="form-control" rows="3" required><?php echo e(old('address', $company->address)); ?></textarea></div>
                <div class="row">
                    <div class="col-md-6"><div class="form-group"><label>City *</label><input name="city" class="form-control" value="<?php echo e(old('city', $company->city)); ?>" required></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Pincode *</label><input name="pincode" class="form-control" value="<?php echo e(old('pincode', $company->pincode)); ?>" required></div></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><div class="form-group"><label>State *</label><input name="state" class="form-control" value="<?php echo e(old('state', $company->state)); ?>" required></div></div>
                    <div class="col-md-4"><div class="form-group"><label>State Code *</label><input name="state_code" class="form-control" value="<?php echo e(old('state_code', $company->state_code)); ?>" required></div></div>
                </div>
            </div>
        </div>
        <hr><h5>Bank Details</h5>
        <div class="row">
            <div class="col-md-3"><div class="form-group"><label>Bank Name</label><input name="bank_name" class="form-control" value="<?php echo e(old('bank_name', $company->bank_name)); ?>"></div></div>
            <div class="col-md-3"><div class="form-group"><label>Account Number</label><input name="bank_account_number" class="form-control" value="<?php echo e(old('bank_account_number', $company->bank_account_number)); ?>"></div></div>
            <div class="col-md-3"><div class="form-group"><label>IFSC</label><input name="bank_ifsc" class="form-control" value="<?php echo e(old('bank_ifsc', $company->bank_ifsc)); ?>"></div></div>
            <div class="col-md-3"><div class="form-group"><label>Branch</label><input name="bank_branch" class="form-control" value="<?php echo e(old('bank_branch', $company->bank_branch)); ?>"></div></div>
        </div>
        <hr><h5>Invoice Settings</h5>
        <div class="row">
            <div class="col-md-3"><div class="form-group"><label>Invoice Prefix *</label><input name="invoice_prefix" class="form-control" value="<?php echo e(old('invoice_prefix', $company->invoice_prefix)); ?>" required></div></div>
            <div class="col-md-3"><div class="form-group"><label>Financial Year *</label><input name="financial_year" class="form-control" value="<?php echo e(old('financial_year', $company->financial_year)); ?>" required></div></div>
            <div class="col-md-3"><div class="form-group"><label>Currency *</label><input name="currency" class="form-control" value="<?php echo e(old('currency', $company->currency)); ?>" required></div></div>
            <div class="col-md-3"><div class="form-group"><label>Status</label><div class="custom-control custom-switch mt-2"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" <?php echo e(old('is_active', $company->is_active) ? 'checked' : ''); ?>><label class="custom-control-label" for="is_active">Active</label></div></div></div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Invoice Display Options</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" name="show_discount" class="custom-control-input" id="show_discount" value="1" <?php echo e(old('show_discount', $company->show_discount) ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="show_discount">Show Discount Column</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="show_tax" class="custom-control-input" id="show_tax" value="1" <?php echo e(old('show_tax', $company->show_tax) ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="show_tax">Show Tax Column</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="show_hsn" class="custom-control-input" id="show_hsn" value="1" <?php echo e(old('show_hsn', $company->show_hsn) ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="show_hsn">Show HSN Column</label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="form-group">
                    <label>GSTIN Display Options</label>
                    <div class="custom-control custom-switch mt-2">
                        <input type="checkbox" name="show_company_gstin" class="custom-control-input" id="show_company_gstin" value="1" <?php echo e(old('show_company_gstin', $company->show_company_gstin) ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="show_company_gstin">Show Company GSTIN</label>
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="show_client_gstin" class="custom-control-input" id="show_client_gstin" value="1" <?php echo e(old('show_client_gstin', $company->show_client_gstin) ? 'checked' : ''); ?>>
                        <label class="custom-control-label" for="show_client_gstin">Show Client GSTIN</label>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Invoice PDF Copies</label>
                    <select name="invoice_copies" class="form-control" required>
                        <option value="1" <?php echo e(old('invoice_copies', $company->invoice_copies) == 1 ? 'selected' : ''); ?>>1 Copy (Customer Only)</option>
                        <option value="2" <?php echo e(old('invoice_copies', $company->invoice_copies) == 2 ? 'selected' : ''); ?>>2 Copies (Customer + Office)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update Company</button>
        <a href="<?php echo e(route('admin.companies.index')); ?>" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/companies/edit.blade.php ENDPATH**/ ?>