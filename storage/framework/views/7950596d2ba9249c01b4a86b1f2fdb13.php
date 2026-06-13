<?php $__env->startSection('title', 'New Invoice'); ?>

<?php $__env->startSection('content_header'); ?>
<div class="d-flex justify-content-between align-items-center">
    <h1>New Invoice</h1>
    <a href="<?php echo e(route('admin.invoices.index')); ?>" class="btn btn-default">← Back to Invoices</a>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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


<?php
  $itemsJson = $items->map(fn($i) => [
    'id'          => $i->id,
    'name'        => $i->name,
    'unit'        => $i->unit,
    'hsn_code'    => $i->hsn_code ?? '',
    'rate'        => (float) $i->selling_price,
    'tax_rule_id' => $i->tax_rule_id,
    'cgst_rate'   => $i->taxRule ? (float)$i->taxRule->cgst_rate : 0,
    'sgst_rate'   => $i->taxRule ? (float)$i->taxRule->sgst_rate : 0,
    'igst_rate'   => $i->taxRule ? (float)$i->taxRule->igst_rate : 0,
  ])->keyBy('id');
?>

<form method="POST" action="<?php echo e(route('admin.invoices.store')); ?>" id="invoice-form">
<?php echo csrf_field(); ?>

<div class="row">
  
  <div class="col-md-8">

    <div class="card">
      <div class="card-header"><h3 class="card-title">Invoice Details</h3></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Company <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-control <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
              <option value="">— Select Company —</option>
              <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $co): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($co->id); ?>"
                  data-prefix="<?php echo e($co->invoice_prefix); ?>"
                  data-counter="<?php echo e($co->invoice_counter); ?>"
                  data-fy="<?php echo e($co->financial_year); ?>"
                  <?php echo e(old('company_id') == $co->id ? 'selected' : ''); ?>>
                  <?php echo e($co->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['company_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="col-md-6 form-group">
            <label>Client <span class="text-danger">*</span></label>
            <select name="client_id" id="client_id" class="form-control <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
              <option value="">— Select Client —</option>
              <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cl): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($cl->id); ?>"
                  data-state="<?php echo e($cl->billing_state_code); ?>"
                  <?php echo e(old('client_id') == $cl->id ? 'selected' : ''); ?>>
                  <?php echo e($cl->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label>Invoice Number <span class="text-danger">*</span></label>
            <input name="invoice_number" id="invoice_number" type="text"
                   class="form-control <?php $__errorArgs = ['invoice_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('invoice_number')); ?>" required placeholder="Auto-generated">
            <?php $__errorArgs = ['invoice_number'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="col-md-4 form-group">
            <label>Invoice Date <span class="text-danger">*</span></label>
            <input name="invoice_date" type="date"
                   class="form-control <?php $__errorArgs = ['invoice_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('invoice_date', date('Y-m-d'))); ?>" required>
            <?php $__errorArgs = ['invoice_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
          <div class="col-md-4 form-group">
            <label>Due Date</label>
            <input name="due_date" type="date"
                   class="form-control <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('due_date')); ?>">
            <?php $__errorArgs = ['due_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label>Supply Type <span class="text-danger">*</span></label>
            <select name="supply_type" id="supply_type" class="form-control" required>
              <option value="intra" <?php echo e(old('supply_type','intra') === 'intra' ? 'selected' : ''); ?>>Intra-state (CGST + SGST)</option>
              <option value="inter" <?php echo e(old('supply_type') === 'inter' ? 'selected' : ''); ?>>Inter-state (IGST)</option>
            </select>
          </div>
          <div class="col-md-4 form-group">
            <label>Financial Year <span class="text-danger">*</span></label>
            <input name="financial_year" id="financial_year" type="text"
                   class="form-control <?php $__errorArgs = ['financial_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   value="<?php echo e(old('financial_year', '2025-26')); ?>" required placeholder="e.g. 2025-26">
            <?php $__errorArgs = ['financial_year'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><div class="invalid-feedback"><?php echo e($message); ?></div><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
          </div>
        </div>
      </div>
    </div>

    
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Line Items</h3>
        <div class="card-tools">
          <button type="button" id="add-line" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> Add Line
          </button>
        </div>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table table-sm table-bordered mb-0" id="lines-table">
            <thead class="thead-dark">
              <tr>
                <th style="width:32px">#</th>
                <th style="min-width:160px">Item / Description</th>
                <th style="width:70px">HSN</th>
                <th style="width:80px">Qty</th>
                <th style="width:60px">Unit</th>
                <th style="width:100px">Rate (₹)</th>
                <th style="width:65px">Disc %</th>
                <th style="width:130px">Tax Rule</th>
                <th style="width:100px" class="text-right">Amount (₹)</th>
                <th style="width:32px"></th>
              </tr>
            </thead>
            <tbody id="lines-body">
              
            </tbody>
          </table>
        </div>
      </div>
    </div>

    
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Notes <small class="text-muted">(shown on invoice)</small></label>
            <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes')); ?></textarea>
          </div>
          <div class="col-md-6 form-group">
            <label>Terms &amp; Conditions</label>
            <textarea name="terms" class="form-control" rows="3"><?php echo e(old('terms', 'Payment as per agreed schedule. Final amount may vary based on actual site measurements.')); ?></textarea>
          </div>
        </div>
      </div>
    </div>

  </div>

  
  <div class="col-md-4">
    <div class="card card-primary">
      <div class="card-header"><h3 class="card-title">Summary</h3></div>
      <div class="card-body p-0">
        <table class="table table-sm mb-0">
          <tr><td>Subtotal</td>          <td class="text-right" id="sum-subtotal">₹ 0.00</td></tr>
          <tr id="row-discount" style="display:none"><td>Discount</td><td class="text-right text-danger" id="sum-discount"></td></tr>
          <tr id="row-cgst" style="display:none"><td>CGST</td>           <td class="text-right" id="sum-cgst"></td></tr>
          <tr id="row-sgst" style="display:none"><td>SGST</td>           <td class="text-right" id="sum-sgst"></td></tr>
          <tr id="row-igst" style="display:none"><td>IGST</td>           <td class="text-right" id="sum-igst"></td></tr>
          <tr class="table-primary font-weight-bold">
            <td>Grand Total</td>
            <td class="text-right" id="sum-grand">₹ 0.00</td>
          </tr>
        </table>
      </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block btn-lg">
      <i class="fas fa-save"></i> Save Invoice (Draft)
    </button>
  </div>
</div>

</form>


<template id="line-template">
  <tr class="line-row" data-index="__IDX__">
    <td class="line-num text-muted small pt-2"></td>
    <td>
      <select class="form-control form-control-sm item-select mb-1" name="lines[__IDX__][item_id]">
        <option value="">— Custom —</option>
        <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $it): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($it->id); ?>"
            data-unit="<?php echo e($it->unit); ?>"
            data-hsn="<?php echo e($it->hsn_code ?? ''); ?>"
            data-rate="<?php echo e($it->selling_price); ?>"
            data-taxrule="<?php echo e($it->tax_rule_id); ?>">
            <?php echo e($it->name); ?>

          </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
      <input type="text" class="form-control form-control-sm line-desc" name="lines[__IDX__][description]" placeholder="Description" required>
      <input type="hidden" class="line-tax-rule" name="lines[__IDX__][tax_rule_id]" value="">
    </td>
    <td><input type="text" class="form-control form-control-sm line-hsn" name="lines[__IDX__][hsn_code]" placeholder="HSN" maxlength="20"></td>
    <td><input type="number" class="form-control form-control-sm line-qty" name="lines[__IDX__][quantity]" value="1" step="0.001" min="0.001" required></td>
    <td><input type="text" class="form-control form-control-sm line-unit" name="lines[__IDX__][unit]" value="Nos" maxlength="20" required></td>
    <td><input type="number" class="form-control form-control-sm line-rate" name="lines[__IDX__][rate]" value="0" step="0.01" min="0" required></td>
    <td><input type="number" class="form-control form-control-sm line-disc" name="lines[__IDX__][discount_pct]" value="0" step="0.01" min="0" max="100"></td>
    <td>
      <select class="form-control form-control-sm line-taxrule-select" data-for="tax_rule_id">
        <option value="">None (Exempt)</option>
        <?php $__currentLoopData = $taxRules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tr): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($tr->id); ?>"
            data-cgst="<?php echo e($tr->cgst_rate); ?>"
            data-sgst="<?php echo e($tr->sgst_rate); ?>"
            data-igst="<?php echo e($tr->igst_rate); ?>">
            <?php echo e($tr->name); ?>

          </option>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </select>
    </td>
    <td class="text-right line-total pt-2 font-weight-bold">0.00</td>
    <td class="text-center">
      <button type="button" class="btn btn-xs btn-danger remove-line" title="Remove">&times;</button>
    </td>
  </tr>
</template>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('js'); ?>
<script>
(function() {
  const ITEMS = <?php echo json_encode($itemsJson, 15, 512) ?>;
  let rowIndex = 0;

  // ── Generate invoice number when company changes ──
  document.getElementById('company_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) return;
    const prefix  = opt.dataset.prefix || 'INV';
    const fy      = (opt.dataset.fy || '2025-26').replace('-', '');
    const counter = String(opt.dataset.counter || 1).padStart(4, '0');
    const invNo   = document.getElementById('invoice_number');
    if (!invNo.value) {
      invNo.value = `${prefix}-${fy}-${counter}`;
    }
    document.getElementById('financial_year').value = opt.dataset.fy || '';
  });

  // ── Add row ──
  document.getElementById('add-line').addEventListener('click', addRow);

  function addRow(prefill) {
    const tmpl = document.getElementById('line-template').innerHTML
      .replaceAll('__IDX__', rowIndex);
    const tbody = document.getElementById('lines-body');
    tbody.insertAdjacentHTML('beforeend', tmpl);
    const row = tbody.lastElementChild;
    row.querySelector('.line-num').textContent = tbody.children.length;
    attachRowEvents(row, prefill);
    rowIndex++;
    recalcAll();
  }

  function attachRowEvents(row, prefill) {
    const itemSel    = row.querySelector('.item-select');
    const descInput  = row.querySelector('.line-desc');
    const hsnInput   = row.querySelector('.line-hsn');
    const qtyInput   = row.querySelector('.line-qty');
    const unitInput  = row.querySelector('.line-unit');
    const rateInput  = row.querySelector('.line-rate');
    const discInput  = row.querySelector('.line-disc');
    const taxSel     = row.querySelector('.line-taxrule-select');
    const taxHidden  = row.querySelector('.line-tax-rule');

    // Item dropdown auto-fill
    itemSel.addEventListener('change', function() {
      const id = parseInt(this.value);
      if (id && ITEMS[id]) {
        const it = ITEMS[id];
        descInput.value = it.name;
        hsnInput.value  = it.hsn_code;
        unitInput.value = it.unit;
        rateInput.value = it.rate;
        // Select matching tax rule
        for (let opt of taxSel.options) {
          if (parseInt(opt.value) === it.tax_rule_id) {
            taxSel.value = opt.value;
            break;
          }
        }
      }
      syncTaxHidden(taxSel, taxHidden);
      recalcAll();
    });

    taxSel.addEventListener('change', function() {
      syncTaxHidden(this, taxHidden);
      recalcAll();
    });

    [qtyInput, rateInput, discInput].forEach(el => el.addEventListener('input', recalcAll));

    row.querySelector('.remove-line').addEventListener('click', function() {
      row.remove();
      renumberRows();
      recalcAll();
    });

    if (prefill) {
      descInput.value = prefill.description || '';
      hsnInput.value  = prefill.hsn || '';
      qtyInput.value  = prefill.qty || 1;
      unitInput.value = prefill.unit || 'Nos';
      rateInput.value = prefill.rate || 0;
    }
    syncTaxHidden(taxSel, taxHidden);
  }

  function syncTaxHidden(sel, hidden) {
    hidden.value = sel.value;
  }

  function renumberRows() {
    document.querySelectorAll('#lines-body .line-row').forEach((row, i) => {
      row.querySelector('.line-num').textContent = i + 1;
    });
  }

  function recalcAll() {
    const supply = document.getElementById('supply_type').value;
    let subtotal = 0, discountTotal = 0, cgstTotal = 0, sgstTotal = 0, igstTotal = 0;

    document.querySelectorAll('#lines-body .line-row').forEach(row => {
      const qty      = parseFloat(row.querySelector('.line-qty').value)  || 0;
      const rate     = parseFloat(row.querySelector('.line-rate').value) || 0;
      const discPct  = parseFloat(row.querySelector('.line-disc').value) || 0;
      const taxSel   = row.querySelector('.line-taxrule-select');
      const taxOpt   = taxSel.options[taxSel.selectedIndex];

      const lineSub  = qty * rate;
      const discAmt  = lineSub * discPct / 100;
      const taxable  = lineSub - discAmt;

      let cgst = 0, sgst = 0, igst = 0;
      if (taxOpt && taxOpt.value) {
        if (supply === 'intra') {
          cgst = taxable * (parseFloat(taxOpt.dataset.cgst) || 0) / 100;
          sgst = taxable * (parseFloat(taxOpt.dataset.sgst) || 0) / 100;
        } else {
          igst = taxable * (parseFloat(taxOpt.dataset.igst) || 0) / 100;
        }
      }

      const lineTotal = taxable + cgst + sgst + igst;
      row.querySelector('.line-total').textContent = fmt(lineTotal);

      subtotal     += lineSub;
      discountTotal+= discAmt;
      cgstTotal    += cgst;
      sgstTotal    += sgst;
      igstTotal    += igst;
    });

    const taxable   = subtotal - discountTotal;
    const grandTotal= taxable + cgstTotal + sgstTotal + igstTotal;

    set('sum-subtotal', '₹ ' + fmt(subtotal));
    set('sum-grand',    '₹ ' + fmt(grandTotal));

    toggle('row-discount', discountTotal > 0);
    set('sum-discount', '- ₹ ' + fmt(discountTotal));

    toggle('row-cgst', cgstTotal > 0.001);
    set('sum-cgst', '₹ ' + fmt(cgstTotal));
    toggle('row-sgst', sgstTotal > 0.001);
    set('sum-sgst', '₹ ' + fmt(sgstTotal));
    toggle('row-igst', igstTotal > 0.001);
    set('sum-igst', '₹ ' + fmt(igstTotal));
  }

  document.getElementById('supply_type').addEventListener('change', recalcAll);

  function fmt(n) { return n.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}); }
  function set(id, val) { const el = document.getElementById(id); if (el) el.textContent = val; }
  function toggle(id, show) { const el = document.getElementById(id); if (el) el.style.display = show ? '' : 'none'; }

  // Start with one empty row
  addRow();
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('adminlte::page', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/html/resources/views/admin/invoices/create.blade.php ENDPATH**/ ?>