@extends('adminlte::page')
@section('title', 'New Invoice')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>New Invoice</h1>
    <a href="{{ route('admin.invoices.index') }}" class="btn btn-default">← Back to Invoices</a>
</div>
@endsection

@section('content')
@if($errors->any())
    <x-adminlte-alert theme="danger" dismissable>
        <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </x-adminlte-alert>
@endif

{{-- Item master data passed to JS --}}
@php
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
@endphp

<form method="POST" action="{{ route('admin.invoices.store') }}" id="invoice-form">
@csrf

<div class="row">
  {{-- Left column: invoice details --}}
  <div class="col-md-8">

    <div class="card">
      <div class="card-header"><h3 class="card-title">Invoice Details</h3></div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Company <span class="text-danger">*</span></label>
            <select name="company_id" id="company_id" class="form-control @error('company_id') is-invalid @enderror" required>
              <option value="">— Select Company —</option>
              @foreach($companies as $co)
                <option value="{{ $co->id }}"
                  data-prefix="{{ $co->invoice_prefix }}"
                  data-counter="{{ $co->invoice_counter }}"
                  data-fy="{{ $co->financial_year }}"
                  {{ old('company_id') == $co->id ? 'selected' : '' }}>
                  {{ $co->name }}
                </option>
              @endforeach
            </select>
            @error('company_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-6 form-group">
            <label>Client <span class="text-danger">*</span></label>
            <select name="client_id" id="client_id" class="form-control @error('client_id') is-invalid @enderror" required>
              <option value="">— Select Client —</option>
              @foreach($clients as $cl)
                <option value="{{ $cl->id }}"
                  data-state="{{ $cl->billing_state_code }}"
                  {{ old('client_id') == $cl->id ? 'selected' : '' }}>
                  {{ $cl->name }}
                </option>
              @endforeach
            </select>
            @error('client_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label>Invoice Number <span class="text-danger">*</span></label>
            <input name="invoice_number" id="invoice_number" type="text"
                   class="form-control @error('invoice_number') is-invalid @enderror"
                   value="{{ old('invoice_number') }}" required placeholder="Auto-generated">
            @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 form-group">
            <label>Invoice Date <span class="text-danger">*</span></label>
            <input name="invoice_date" type="date"
                   class="form-control @error('invoice_date') is-invalid @enderror"
                   value="{{ old('invoice_date', date('Y-m-d')) }}" required>
            @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 form-group">
            <label>Due Date</label>
            <input name="due_date" type="date"
                   class="form-control @error('due_date') is-invalid @enderror"
                   value="{{ old('due_date') }}">
            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label>Supply Type <span class="text-danger">*</span></label>
            <select name="supply_type" id="supply_type" class="form-control" required>
              <option value="intra" {{ old('supply_type','intra') === 'intra' ? 'selected' : '' }}>Intra-state (CGST + SGST)</option>
              <option value="inter" {{ old('supply_type') === 'inter' ? 'selected' : '' }}>Inter-state (IGST)</option>
            </select>
          </div>
          <div class="col-md-4 form-group">
            <label>Financial Year <span class="text-danger">*</span></label>
            <input name="financial_year" id="financial_year" type="text"
                   class="form-control @error('financial_year') is-invalid @enderror"
                   value="{{ old('financial_year', '2025-26') }}" required placeholder="e.g. 2025-26">
            @error('financial_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
    </div>

    {{-- Line items --}}
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
              {{-- Rows inserted by JS --}}
            </tbody>
          </table>
        </div>
      </div>
    </div>

    {{-- Notes & Terms --}}
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-md-6 form-group">
            <label>Notes <small class="text-muted">(shown on invoice)</small></label>
            <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
          </div>
          <div class="col-md-6 form-group">
            <label>Terms &amp; Conditions</label>
            <textarea name="terms" class="form-control" rows="3">{{ old('terms', 'Payment as per agreed schedule. Final amount may vary based on actual site measurements.') }}</textarea>
          </div>
        </div>
      </div>
    </div>

  </div>{{-- col-md-8 --}}

  {{-- Right column: totals --}}
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

{{-- Row template (hidden) --}}
<template id="line-template">
  <tr class="line-row" data-index="__IDX__">
    <td class="line-num text-muted small pt-2"></td>
    <td>
      <select class="form-control form-control-sm item-select mb-1" name="lines[__IDX__][item_id]">
        <option value="">— Custom —</option>
        @foreach($items as $it)
          <option value="{{ $it->id }}"
            data-unit="{{ $it->unit }}"
            data-hsn="{{ $it->hsn_code ?? '' }}"
            data-rate="{{ $it->selling_price }}"
            data-taxrule="{{ $it->tax_rule_id }}">
            {{ $it->name }}
          </option>
        @endforeach
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
        @foreach($taxRules as $tr)
          <option value="{{ $tr->id }}"
            data-cgst="{{ $tr->cgst_rate }}"
            data-sgst="{{ $tr->sgst_rate }}"
            data-igst="{{ $tr->igst_rate }}">
            {{ $tr->name }}
          </option>
        @endforeach
      </select>
    </td>
    <td class="text-right line-total pt-2 font-weight-bold">0.00</td>
    <td class="text-center">
      <button type="button" class="btn btn-xs btn-danger remove-line" title="Remove">&times;</button>
    </td>
  </tr>
</template>
@endsection

@push('js')
<script>
(function() {
  const ITEMS = @json($itemsJson);
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
@endpush
