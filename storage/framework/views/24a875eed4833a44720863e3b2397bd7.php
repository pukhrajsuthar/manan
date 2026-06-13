<?php $__env->startSection('title', 'Invoice ' . $invoice->invoice_number); ?>
<?php $__env->startSection('content_header'); ?>
    <h1>Invoice: <?php echo e($invoice->invoice_number); ?>

        <?php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','cancelled'=>'danger']; ?>
        <span class="badge badge-<?php echo e($colors[$invoice->status] ?? 'secondary'); ?> ml-2"><?php echo e(ucfirst($invoice->status)); ?></span>
    </h1>
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

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Invoice Details</h3>
                <div class="card-tools">
                    <a href="<?php echo e(route('admin.invoices.print', $invoice)); ?>" target="_blank" class="btn btn-sm btn-secondary">
                        <i class="fas fa-print"></i> Print
                    </a>
                    <a href="<?php echo e(route('admin.invoices.pdf', $invoice)); ?>" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    <?php if($invoice->status === 'draft'): ?>
                        <form action="<?php echo e(route('admin.invoices.send', $invoice)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button class="btn btn-sm btn-info">Mark Sent</button>
                        </form>
                    <?php endif; ?>
                    <?php if(!in_array($invoice->status, ['paid','cancelled'])): ?>
                        <form action="<?php echo e(route('admin.invoices.cancel', $invoice)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Cancel this invoice?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('PATCH'); ?>
                            <button class="btn btn-sm btn-warning">Cancel</button>
                        </form>
                    <?php endif; ?>
                    <?php if($invoice->status !== 'paid'): ?>
                        <form action="<?php echo e(route('admin.invoices.destroy', $invoice)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Delete permanently?')">
                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Company</strong><br>
                        <?php echo e($invoice->company->name ?? '—'); ?><br>
                        <?php echo e($invoice->company->address ?? ''); ?>, <?php echo e($invoice->company->city ?? ''); ?><br>
                        GSTIN: <?php echo e($invoice->company->gstin ?? '—'); ?>

                    </div>
                    <div class="col-md-6">
                        <strong>Client</strong><br>
                        <?php echo e($invoice->client->name ?? '—'); ?><br>
                        <?php echo e($invoice->client->billing_address ?? ''); ?>, <?php echo e($invoice->client->billing_city ?? ''); ?><br>
                        GSTIN: <?php echo e($invoice->client->gstin ?? '—'); ?>

                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4"><strong>Invoice Date:</strong> <?php echo e($invoice->invoice_date?->format('d M Y')); ?></div>
                    <div class="col-md-4"><strong>Due Date:</strong> <?php echo e($invoice->due_date?->format('d M Y')); ?></div>
                    <div class="col-md-4"><strong>Supply Type:</strong> <?php echo e(ucfirst($invoice->supply_type ?? '—')); ?></div>
                </div>
                <hr>
                <table class="table table-sm table-bordered mt-3">
                    <thead class="thead-light">
                        <tr><th>#</th><th>Item</th><th>HSN</th><th>Qty</th><th>Unit</th><th>Rate</th><th>Disc.</th><th>Tax</th><th>Amount</th></tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $invoice->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($i + 1); ?></td>
                            <td><?php echo e($line->item->name ?? $line->description); ?></td>
                            <td><?php echo e($line->hsn_code ?? '—'); ?></td>
                            <td><?php echo e($line->quantity); ?></td>
                            <td><?php echo e($line->unit); ?></td>
                            <td>₹<?php echo e(number_format($line->rate, 2)); ?></td>
                            <td><?php echo e($line->discount_percent ?? 0); ?>%</td>
                            <td><?php echo e($line->item->taxRule->name ?? '—'); ?></td>
                            <td>₹<?php echo e(number_format($line->total, 2)); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot>
                        <tr><td colspan="8" class="text-right"><strong>Subtotal</strong></td><td>₹<?php echo e(number_format($invoice->subtotal, 2)); ?></td></tr>
                        <?php if($invoice->discount_amount > 0): ?>
                        <tr><td colspan="8" class="text-right">Discount</td><td>-₹<?php echo e(number_format($invoice->discount_amount, 2)); ?></td></tr>
                        <?php endif; ?>
                        <tr><td colspan="8" class="text-right">Taxable Amount</td><td>₹<?php echo e(number_format($invoice->taxable_amount, 2)); ?></td></tr>
                        <?php if($invoice->cgst_total > 0): ?>
                        <tr><td colspan="8" class="text-right">CGST</td><td>₹<?php echo e(number_format($invoice->cgst_total, 2)); ?></td></tr>
                        <tr><td colspan="8" class="text-right">SGST</td><td>₹<?php echo e(number_format($invoice->sgst_total, 2)); ?></td></tr>
                        <?php endif; ?>
                        <?php if($invoice->igst_total > 0): ?>
                        <tr><td colspan="8" class="text-right">IGST</td><td>₹<?php echo e(number_format($invoice->igst_total, 2)); ?></td></tr>
                        <?php endif; ?>
                        <tr class="table-active"><td colspan="8" class="text-right"><strong>Grand Total</strong></td><td><strong>₹<?php echo e(number_format($invoice->grand_total, 2)); ?></strong></td></tr>
                    </tfoot>
                </table>
                <?php if($invoice->notes): ?>
                    <p><strong>Notes:</strong> <?php echo e($invoice->notes); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">

        
        <div class="card">
            <div class="card-header"><h3 class="card-title">Payment Summary</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr><td>Grand Total</td><td class="text-right font-weight-bold">₹<?php echo e(number_format($invoice->grand_total, 2)); ?></td></tr>
                    <tr><td>Amount Paid</td><td class="text-right text-success font-weight-bold">₹<?php echo e(number_format($invoice->amount_paid, 2)); ?></td></tr>
                    <tr class="<?php echo e($invoice->balance_due > 0 ? 'table-danger' : 'table-success'); ?>">
                        <td>Balance Due</td>
                        <td class="text-right font-weight-bold">₹<?php echo e(number_format($invoice->balance_due, 2)); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        
        <?php if($invoice->balance_due > 0 && $invoice->status !== 'cancelled'): ?>
        <div class="card card-success">
            <div class="card-header"><h3 class="card-title">Record Payment</h3></div>
            <div class="card-body">
                <form action="<?php echo e(route('admin.invoices.payment', $invoice)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="form-group">
                        <label>Amount (₹) <span class="text-danger">*</span></label>
                        <input name="amount" type="number" step="0.01" min="0.01"
                               max="<?php echo e($invoice->balance_due); ?>"
                               class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               placeholder="0.00" value="<?php echo e(old('amount')); ?>" required>
                        <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label>Payment Date <span class="text-danger">*</span></label>
                        <input name="payment_date" type="date"
                               class="form-control <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('payment_date', date('Y-m-d'))); ?>" required>
                        <?php $__errorArgs = ['payment_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label>Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="cash"          <?php echo e(old('payment_method','cash') === 'cash'          ? 'selected' : ''); ?>>Cash</option>
                            <option value="cheque"        <?php echo e(old('payment_method') === 'cheque'        ? 'selected' : ''); ?>>Cheque</option>
                            <option value="bank_transfer" <?php echo e(old('payment_method') === 'bank_transfer' ? 'selected' : ''); ?>>Bank Transfer / NEFT</option>
                            <option value="upi"           <?php echo e(old('payment_method') === 'upi'           ? 'selected' : ''); ?>>UPI</option>
                        </select>
                        <?php $__errorArgs = ['payment_method'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    <div class="form-group">
                        <label>Reference / Cheque No. / UTR</label>
                        <input name="reference" type="text" class="form-control"
                               placeholder="Optional" value="<?php echo e(old('reference')); ?>" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control" rows="2"
                                  placeholder="Optional note"><?php echo e(old('note')); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Record Payment
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        
        <?php if($invoice->paymentLogs->isNotEmpty()): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment History</h3>
                <div class="card-tools">
                    <span class="badge badge-info"><?php echo e($invoice->paymentLogs->count()); ?> entries</span>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>Date</th>
                            <th>Method</th>
                            <th class="text-right">Amount</th>
                            <th class="text-right">Balance After</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $__currentLoopData = $invoice->paymentLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <?php echo e($log->payment_date->format('d M Y')); ?>

                                <?php if($log->reference): ?>
                                    <br><small class="text-muted"><?php echo e($log->reference); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php
                                  $methodLabels = ['cash'=>'Cash','cheque'=>'Cheque','bank_transfer'=>'Bank Transfer','upi'=>'UPI'];
                                ?>
                                <span class="badge badge-secondary"><?php echo e($methodLabels[$log->payment_method] ?? $log->payment_method); ?></span>
                                <?php if($log->note): ?>
                                    <br><small class="text-muted"><?php echo e($log->note); ?></small>
                                <?php endif; ?>
                            </td>
                            <td class="text-right text-success font-weight-bold">
                                ₹<?php echo e(number_format($log->amount, 2)); ?>

                            </td>
                            <td class="text-right <?php echo e($log->balance_after > 0 ? 'text-danger' : 'text-success'); ?>">
                                ₹<?php echo e(number_format($log->balance_after, 2)); ?>

                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

        <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-default btn-block mt-2">← Back to Invoices</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/invoices/show.blade.php ENDPATH**/ ?>