<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo e($invoice->invoice_number); ?></title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    font-family: "DejaVu Sans", Arial, sans-serif;
    font-size: 11pt;
    color: #111;
    background: #fff;
}

/* ── Toolbar (browser only) ── */
#toolbar {
    background: #2c3e50;
    padding: 10px 18px;
}
#toolbar a, #toolbar button {
    display: inline-block;
    padding: 7px 16px;
    border-radius: 4px;
    font-size: 13px;
    font-weight: bold;
    cursor: pointer;
    text-decoration: none;
    border: none;
    margin-right: 6px;
}
.btn-print  { background: #27ae60; color: #fff; }
.btn-pdf    { background: #e74c3c; color: #fff; }
.btn-back   { background: #95a5a6; color: #fff; }

/* ── Copy wrapper ── */
.copy {
    width: 190mm;
    margin: 0 auto;
    padding: 8mm 0 6mm;
}

/* ── Copy label ── */
.copy-label {
    text-align: right;
    font-size: 8pt;
    font-weight: bold;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 3px;
}

/* ── Separator between copies ── */
.separator {
    width: 190mm;
    margin: 0 auto;
    text-align: center;
    border-top: 1px dashed #bbb;
    border-bottom: 1px dashed #bbb;
    padding: 3px 0;
    font-size: 8pt;
    color: #aaa;
    letter-spacing: 3px;
}

/* ── Header table ── */
.header-table { width: 100%; border-bottom: 2px solid #2c3e50; padding-bottom: 6px; margin-bottom: 8px; }
.company-name { font-size: 17pt; font-weight: bold; color: #2c3e50; line-height: 1.2; }
.company-sub  { font-size: 8pt; color: #555; margin-top: 2px; line-height: 1.5; }
.inv-title    { font-size: 13pt; font-weight: bold; color: #2c3e50; text-align: right; }
.inv-meta     { font-size: 8pt; text-align: right; line-height: 1.8; color: #444; }
.inv-meta strong { color: #111; }

/* ── Status badge ── */
.badge {
    display: inline-block;
    padding: 1px 7px;
    border-radius: 3px;
    font-size: 7pt;
    font-weight: bold;
    text-transform: uppercase;
    letter-spacing: 1px;
}
.badge-draft     { background: #bdc3c7; color: #333; }
.badge-sent      { background: #3498db; color: #fff; }
.badge-paid      { background: #27ae60; color: #fff; }
.badge-cancelled { background: #e74c3c; color: #fff; }

/* ── Address boxes ── */
.addr-table { width: 100%; margin-bottom: 8px; }
.addr-box {
    background: #f8f9fa;
    border: 1px solid #ddd;
    padding: 6px 8px;
    vertical-align: top;
    width: 49%;
}
.addr-label { font-size: 7pt; font-weight: bold; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-bottom: 2px; }
.addr-name  { font-size: 11pt; font-weight: bold; color: #2c3e50; margin-bottom: 2px; }
.addr-line  { font-size: 8.5pt; color: #444; line-height: 1.5; }

/* ── Items table ── */
table.items {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 6px;
    font-size: 8.5pt;
}
table.items thead tr { background: #2c3e50; color: #fff; }
table.items thead th {
    padding: 5px 4px;
    text-align: left;
    font-size: 8pt;
    white-space: nowrap;
    border: 1px solid #1a252f;
}
table.items thead th.r { text-align: right; }
table.items tbody tr:nth-child(even) { background: #f5f6f7; }
table.items tbody td {
    padding: 3px 4px;
    border: 1px solid #e0e0e0;
    vertical-align: top;
    font-size: 8.5pt;
}
table.items tbody td.r { text-align: right; white-space: nowrap; }
table.items tfoot td {
    padding: 3px 4px;
    font-size: 8.5pt;
    border: 1px solid #ccc;
}
table.items tfoot td.r { text-align: right; }
.row-grand td { font-weight: bold; font-size: 10pt; background: #e8f0fe; border-top: 2px solid #2c3e50; }

/* ── Payment summary ── */
.pay-table { font-size: 8.5pt; border-collapse: collapse; }
.pay-table td { padding: 2px 8px; }
.pay-table td.r { text-align: right; }
.pay-balance td { font-weight: bold; color: #c0392b; }
.pay-full td    { font-weight: bold; color: #27ae60; text-align: center; }

/* ── Notes / Terms ── */
.nt-table { width: 100%; margin-top: 6px; font-size: 8pt; }
.nt-label { font-size: 7pt; font-weight: bold; text-transform: uppercase; color: #888; letter-spacing: 1px; margin-bottom: 2px; }
.nt-cell  { vertical-align: top; padding-right: 8mm; width: 50%; color: #444; line-height: 1.5; }

/* ── Signature ── */
.sig-table { width: 100%; margin-top: 10px; border-top: 1px solid #ddd; padding-top: 6px; font-size: 8.5pt; }
.sig-cell  { text-align: center; width: 50%; }
.sig-line  { border-top: 1px solid #333; width: 50mm; margin: 16px auto 4px; }
.sig-sub   { font-size: 7.5pt; color: #555; }

@media print {
    #toolbar { display: none !important; }
    body { background: #fff; }
    .copy { width: 100%; padding: 6mm 8mm; }
    .separator { width: 100%; }
    @page { size: A4 portrait; margin: 0; }
}
</style>
</head>
<body>

<?php if (! ($isPdf)): ?>
<div id="toolbar">
    <button class="btn-print" onclick="window.print()">&#128438; Print</button>
    <a class="btn-pdf" href="<?php echo e(route('admin.invoices.pdf', $invoice)); ?>">&#11015; Download PDF</a>
    <a class="btn-back" href="<?php echo e(route('admin.invoices.show', $invoice)); ?>">&larr; Back</a>
</div>
<?php endif; ?>

<?php
  $copies = [
    ['label' => 'Customer Copy'],
    ['label' => 'Office Copy'],
  ];
  $statusBadge = [
    'draft'     => 'badge-draft',
    'sent'      => 'badge-sent',
    'paid'      => 'badge-paid',
    'cancelled' => 'badge-cancelled',
  ][$invoice->status] ?? 'badge-draft';

  $hasDiscount = $invoice->items->sum('discount_amount') > 0;
  $hasCgst     = $invoice->cgst_total > 0;
  $hasIgst     = $invoice->igst_total > 0;

  // Number of columns in items table
  $extraCols = ($hasDiscount ? 1 : 0) + ($hasCgst ? 2 : 0) + ($hasIgst ? 1 : 0);
  $totalCols = 7 + $extraCols; // #, desc, hsn, qty, unit, rate, [disc], [cgst,sgst], [igst], amount
?>

<?php $__currentLoopData = $copies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ci => $copy): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

  <?php if($ci > 0): ?>
    <?php if($isPdf): ?>
      
      <div style="page-break-after: always;"></div>
    <?php else: ?>
      <div class="separator">&#9988; &nbsp; CUT HERE &nbsp; &#9988;</div>
    <?php endif; ?>
  <?php endif; ?>

  <div class="copy">

    
    <div class="copy-label"><?php echo e($copy['label']); ?></div>

    
    <table class="header-table" style="border-bottom: 2px solid #2c3e50; padding-bottom: 6px; margin-bottom: 8px;">
      <tr>
        <td style="vertical-align: top;">
          <div class="company-name"><?php echo e($invoice->company->name); ?></div>
          <div class="company-sub">
            <?php echo e($invoice->company->address); ?>,
            <?php echo e($invoice->company->city); ?>,
            <?php echo e($invoice->company->state); ?> &ndash; <?php echo e($invoice->company->pincode); ?><br>
            <?php if($invoice->company->phone): ?>Ph: <?php echo e($invoice->company->phone); ?><?php endif; ?>
            <?php if($invoice->company->gstin): ?> &nbsp;|&nbsp; GSTIN: <?php echo e($invoice->company->gstin); ?><?php endif; ?>
          </div>
        </td>
        <td style="vertical-align: top; text-align: right; white-space: nowrap; padding-left: 10px;">
          <div class="inv-title">TAX INVOICE</div>
          <div class="inv-meta">
            <strong><?php echo e($invoice->invoice_number); ?></strong><br>
            Date: <?php echo e($invoice->invoice_date?->format('d M Y')); ?><br>
            <?php if($invoice->due_date): ?>Due: <?php echo e($invoice->due_date->format('d M Y')); ?><br><?php endif; ?>
            <span class="badge <?php echo e($statusBadge); ?>"><?php echo e($invoice->status); ?></span>
            &nbsp; FY <?php echo e($invoice->financial_year); ?>

          </div>
        </td>
      </tr>
    </table>

    
    <table class="addr-table" cellspacing="4">
      <tr>
        <td class="addr-box">
          <div class="addr-label">Bill From</div>
          <div class="addr-name"><?php echo e($invoice->company->name); ?></div>
          <div class="addr-line"><?php echo e($invoice->company->address); ?></div>
          <div class="addr-line"><?php echo e($invoice->company->city); ?>, <?php echo e($invoice->company->state); ?> &ndash; <?php echo e($invoice->company->pincode); ?></div>
          <?php if($invoice->company->gstin): ?><div class="addr-line">GSTIN: <?php echo e($invoice->company->gstin); ?></div><?php endif; ?>
        </td>
        <td style="width: 2%;"></td>
        <td class="addr-box">
          <div class="addr-label">Bill To</div>
          <div class="addr-name"><?php echo e($invoice->client->name); ?></div>
          <div class="addr-line"><?php echo e($invoice->client->billing_address); ?></div>
          <div class="addr-line"><?php echo e($invoice->client->billing_city); ?>, <?php echo e($invoice->client->billing_state); ?> &ndash; <?php echo e($invoice->client->billing_pincode); ?></div>
          <?php if($invoice->client->phone): ?><div class="addr-line">Ph: <?php echo e($invoice->client->phone); ?></div><?php endif; ?>
          <?php if($invoice->client->gstin): ?><div class="addr-line">GSTIN: <?php echo e($invoice->client->gstin); ?></div><?php endif; ?>
        </td>
      </tr>
    </table>

    
    <table class="items">
      <thead>
        <tr>
          <th style="width:22px;">#</th>
          <th>Description</th>
          <th style="width:40px;">HSN</th>
          <th class="r" style="width:46px;">Qty</th>
          <th style="width:30px;">Unit</th>
          <th class="r" style="width:60px;">Rate (&#8377;)</th>
          <?php if($hasDiscount): ?><th class="r" style="width:36px;">Disc.</th><?php endif; ?>
          <?php if($hasCgst): ?>
            <th class="r" style="width:36px;">CGST</th>
            <th class="r" style="width:36px;">SGST</th>
          <?php endif; ?>
          <?php if($hasIgst): ?><th class="r" style="width:36px;">IGST</th><?php endif; ?>
          <th class="r" style="width:70px;">Amount (&#8377;)</th>
        </tr>
      </thead>
      <tbody>
        <?php $__currentLoopData = $invoice->items->sortBy('sort_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr>
          <td><?php echo e($i + 1); ?></td>
          <td><?php echo e($line->description); ?></td>
          <td><?php echo e($line->hsn_code ?? '&mdash;'); ?></td>
          <td class="r"><?php echo e(rtrim(rtrim(number_format($line->quantity, 3), '0'), '.')); ?></td>
          <td><?php echo e($line->unit); ?></td>
          <td class="r"><?php echo e(number_format($line->rate, 2)); ?></td>
          <?php if($hasDiscount): ?>
            <td class="r"><?php echo e($line->discount_percent > 0 ? $line->discount_percent.'%' : '&mdash;'); ?></td>
          <?php endif; ?>
          <?php if($hasCgst): ?>
            <td class="r"><?php echo e($line->cgst_rate > 0 ? $line->cgst_rate.'%' : '&mdash;'); ?></td>
            <td class="r"><?php echo e($line->sgst_rate > 0 ? $line->sgst_rate.'%' : '&mdash;'); ?></td>
          <?php endif; ?>
          <?php if($hasIgst): ?>
            <td class="r"><?php echo e($line->igst_rate > 0 ? $line->igst_rate.'%' : '&mdash;'); ?></td>
          <?php endif; ?>
          <td class="r"><?php echo e(number_format($line->total, 2)); ?></td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      </tbody>
      <tfoot>
        <?php if($invoice->discount_amount > 0): ?>
        <tr>
          <td colspan="<?php echo e($totalCols - 1); ?>" class="r">Subtotal</td>
          <td class="r"><?php echo e(number_format($invoice->subtotal, 2)); ?></td>
        </tr>
        <tr>
          <td colspan="<?php echo e($totalCols - 1); ?>" class="r">Discount</td>
          <td class="r">- <?php echo e(number_format($invoice->discount_amount, 2)); ?></td>
        </tr>
        <?php endif; ?>
        <?php if($hasCgst): ?>
        <tr>
          <td colspan="<?php echo e($totalCols - 1); ?>" class="r">CGST</td>
          <td class="r"><?php echo e(number_format($invoice->cgst_total, 2)); ?></td>
        </tr>
        <tr>
          <td colspan="<?php echo e($totalCols - 1); ?>" class="r">SGST</td>
          <td class="r"><?php echo e(number_format($invoice->sgst_total, 2)); ?></td>
        </tr>
        <?php endif; ?>
        <?php if($hasIgst): ?>
        <tr>
          <td colspan="<?php echo e($totalCols - 1); ?>" class="r">IGST</td>
          <td class="r"><?php echo e(number_format($invoice->igst_total, 2)); ?></td>
        </tr>
        <?php endif; ?>
        <tr class="row-grand">
          <td colspan="<?php echo e($totalCols - 1); ?>" class="r">GRAND TOTAL</td>
          <td class="r">&#8377; <?php echo e(number_format($invoice->grand_total, 2)); ?></td>
        </tr>
      </tfoot>
    </table>

    
    <?php if($invoice->amount_paid > 0 || $invoice->balance_due > 0): ?>
    <table style="width:100%;"><tr><td style="text-align:right;">
      <table class="pay-table" style="display:inline-table;">
        <tr>
          <td>Grand Total</td>
          <td class="r">&#8377; <?php echo e(number_format($invoice->grand_total, 2)); ?></td>
        </tr>
        <tr>
          <td>Amount Received</td>
          <td class="r">&#8377; <?php echo e(number_format($invoice->amount_paid, 2)); ?></td>
        </tr>
        <?php if($invoice->balance_due > 0): ?>
        <tr class="pay-balance">
          <td>Balance Due</td>
          <td class="r">&#8377; <?php echo e(number_format($invoice->balance_due, 2)); ?></td>
        </tr>
        <?php else: ?>
        <tr class="pay-full">
          <td colspan="2">&#10003; PAID IN FULL</td>
        </tr>
        <?php endif; ?>
      </table>
    </td></tr></table>
    <?php endif; ?>

    
    <?php if($invoice->notes || $invoice->terms): ?>
    <table class="nt-table">
      <tr>
        <?php if($invoice->notes): ?>
        <td class="nt-cell">
          <div class="nt-label">Notes</div>
          <?php echo e($invoice->notes); ?>

        </td>
        <?php endif; ?>
        <?php if($invoice->terms): ?>
        <td class="nt-cell">
          <div class="nt-label">Terms &amp; Conditions</div>
          <?php echo e($invoice->terms); ?>

        </td>
        <?php endif; ?>
      </tr>
    </table>
    <?php endif; ?>

    
    <table class="sig-table">
      <tr>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div>Received By (Client)</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div>Authorised Signatory</div>
          <div class="sig-sub"><?php echo e($invoice->company->name); ?></div>
        </td>
      </tr>
    </table>

  </div>

<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</body>
</html>
<?php /**PATH /var/www/html/resources/views/admin/invoices/print.blade.php ENDPATH**/ ?>