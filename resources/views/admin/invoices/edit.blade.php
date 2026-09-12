@extends('adminlte::page')
@section('title', 'Edit Invoice')

@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Edit Invoice: {{ $invoice->invoice_number }}</h1>
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

<form method="POST" action="{{ route('admin.invoices.update', $invoice) }}" id="invoice-form">
@csrf @method('PUT')

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
                  data-show-discount="{{ $co->show_discount ? '1' : '0' }}"
                  data-show-tax="{{ $co->show_tax ? '1' : '0' }}"
                  data-show-hsn="{{ $co->show_hsn ? '1' : '0' }}"
                  {{ old('company_id', $invoice->company_id) == $co->id ? 'selected' : '' }}>
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
                  {{ old('client_id', $invoice->client_id) == $cl->id ? 'selected' : '' }}>
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
                   value="{{ old('invoice_number', $invoice->invoice_number) }}" required>
            @error('invoice_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 form-group">
            <label>Invoice Date <span class="text-danger">*</span></label>
            <input name="invoice_date" type="date"
                   class="form-control @error('invoice_date') is-invalid @enderror"
                   value="{{ old('invoice_date', $invoice->invoice_date->format('Y-m-d')) }}" required>
            @error('invoice_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="col-md-4 form-group">
            <label>Due Date</label>
            <input name="due_date" type="date"
                   class="form-control @error('due_date') is-invalid @enderror"
                   value="{{ old('due_date', $invoice->due_date?->format('Y-m-d')) }}">
            @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label>Supply Type <span class="text-danger">*</span></label>
            <select name="supply_type" id="supply_type" class="form-control" required>
              <option value="intra" {{ old('supply_type', $invoice->supply_type) === 'intra' ? 'selected' : '' }}>Intra-state (CGST + SGST)</option>
              <option value="inter" {{ old('supply_type', $invoice->supply_type) === 'inter' ? 'selected' : '' }}>Inter-state (IGST)</option>
            </select>
          </div>
          <div class="col-md-4 form-group">
            <label>Financial Year <span class="text-danger">*</span></label>
            <input name="financial_year" id="financial_year" type="text"
                   class="form-control @error('financial_year') is-invalid @enderror"
                   value="{{ old('financial_year', $invoice->financial_year) }}" required>
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
            <colgroup>
              <col style="width:32px">
              <col style="min-width:160px">
              <col style="width:70px" class="hsn-column">
              <col style="width:80px">
              <col style="width:60px">
              <col style="width:100px">
              <col style="width:65px" class="discount-column">
              <col style="width:130px" class="tax-column">
              <col style="width:100px">
              <col style="width:32px">
            </colgroup>
            <thead class="thead-dark">
              <tr>
                <th>#</th>
                <th>Item / Description</th>
                <th class="hsn-column">HSN</th>
                <th>Qty</th>
                <th>Unit</th>
                <th>Rate (₹)</th>
                <th class="discount-column">Disc %</th>
                <th class="tax-column">Tax Rule</th>
                <th class="text-right">Amount (₹)</th>
                <th></th>
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
            <textarea name="notes" class="form-control" rows="3">{{ old('notes', $invoice->notes) }}</textarea>
          </div>
          <div class="col-md-6 form-group">
            <label>Terms &amp; Conditions</label>
            <textarea name="terms" class="form-control" rows="3">{{ old('terms', $invoice->terms) }}</textarea>
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
        @php
          $grouped = [];
          foreach($items as $item) {
            try {
              $catName = 'Uncategorized';
              $catOrder = 999;
              if (!empty($item->category_id) && $item->category) {
                $catName = $item->category->name ?? 'Uncategorized';
                $catOrder = $item->category->order ?? 999;
              }
              if (!isset($grouped[$catName])) {
                $grouped[$catName] = ['order' => $catOrder, 'items' => []];
              }
              $grouped[$catName]['items'][] = $item;
            } catch (Exception $e) {
              // Skip items with category errors
              continue;
            }
          }
          uasort($grouped, function($a, $b) { return $a['order'] - $b['order']; });
        @endphp
        @foreach($grouped as $categoryName => $data)
          <optgroup label="{{ $categoryName }}">
            @foreach($data['items'] as $it)
              <option value="{{ $it->id }}"
                data-unit="{{ $it->unit }}"
                data-hsn="{{ $it->hsn_code ?? '' }}"
                data-rate="{{ $it->selling_price }}"
                data-taxrule="{{ $it->tax_rule_id }}">
                {{ $it->name }}
              </option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
      <input type="text" class="form-control form-control-sm line-desc" name="lines[__IDX__][description]" placeholder="Description" required>
      <input type="hidden" class="line-tax-rule" name="lines[__IDX__][tax_rule_id]" value="">
    </td>
    <td class="hsn-column"><input type="text" class="form-control form-control-sm line-hsn" name="lines[__IDX__][hsn_code]" placeholder="HSN" maxlength="20"></td>
    <td><input type="number" class="form-control form-control-sm line-qty" name="lines[__IDX__][quantity]" value="1" step="1" min="1" required></td>
    <td><input type="text" class="form-control form-control-sm line-unit" name="lines[__IDX__][unit]" value="Nos" maxlength="20" required></td>
    <td><input type="number" class="form-control form-control-sm line-rate" name="lines[__IDX__][rate]" value="0" step="0.01" min="0" required></td>
    <td class="discount-column"><input type="number" class="form-control form-control-sm line-disc" name="lines[__IDX__][discount_pct]" value="0" step="0.01" min="0" max="100"></td>
    <td class="tax-column">
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

@push('css')
<style>
  #lines-table.hide-discount .discount-column {
    display: none !important;
    width: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  #lines-table.hide-tax .tax-column {
    display: none !important;
    width: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
  }
  #lines-table.hide-hsn .hsn-column {
    display: none !important;
    width: 0 !important;
    padding: 0 !important;
    margin: 0 !important;
  }
</style>
@endpush

@push('js')
<script>
(function() {
  const ITEMS = @json($itemsJson);
  let rowIndex = 0;

  // ── Generate invoice number when company changes ──
  document.getElementById('company_id').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    if (!opt.value) {
      showAllColumns();
      return;
    }
    const prefix  = opt.dataset.prefix || 'INV';
    const fy      = (opt.dataset.fy || '2025-26').replace('-', '');
    const counter = String(opt.dataset.counter || 1).padStart(4, '0');
    const invNo   = document.getElementById('invoice_number');
    if (!invNo.value) {
      invNo.value = `${prefix}-${fy}-${counter}`;
    }
    document.getElementById('financial_year').value = opt.dataset.fy || '';

    // Toggle discount, tax, and hsn columns based on company settings
    const showDiscount = opt.dataset.showDiscount === '1';
    const showTax      = opt.dataset.showTax === '1';
    const showHsn      = opt.dataset.showHsn === '1';
    toggleColumns(showDiscount, showTax, showHsn);
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
      // Set item dropdown if item_id is provided
      if (prefill.item_id) {
        itemSel.value = prefill.item_id;
      }
      descInput.value = prefill.description || '';
      hsnInput.value  = prefill.hsn || '';
      qtyInput.value  = prefill.qty || 1;
      unitInput.value = prefill.unit || 'Nos';
      rateInput.value = prefill.rate || 0;
      discInput.value = prefill.disc || 0;
      // Set tax rule if provided
      if (prefill.taxRule) {
        taxSel.value = prefill.taxRule;
      }
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

    // Check company settings
    const companySel = document.getElementById('company_id');
    const opt = companySel.options[companySel.selectedIndex];
    const showDiscount = opt && opt.dataset.showDiscount === '1';
    const showTax = opt && opt.dataset.showTax === '1';

    // Toggle discount based on company setting AND if discount amount > 0
    toggle('row-discount', showDiscount && discountTotal > 0);
    set('sum-discount', '- ₹ ' + fmt(discountTotal));

    // Toggle tax rows based on company setting AND if tax amount > 0.001
    toggle('row-cgst', showTax && cgstTotal > 0.001);
    set('sum-cgst', '₹ ' + fmt(cgstTotal));
    toggle('row-sgst', showTax && sgstTotal > 0.001);
    set('sum-sgst', '₹ ' + fmt(sgstTotal));
    toggle('row-igst', showTax && igstTotal > 0.001);
    set('sum-igst', '₹ ' + fmt(igstTotal));
  }

  document.getElementById('supply_type').addEventListener('change', recalcAll);

  function fmt(n) { return n.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2}); }
  function set(id, val) { const el = document.getElementById(id); if (el) el.textContent = val; }
  function toggle(id, show) { const el = document.getElementById(id); if (el) el.style.display = show ? '' : 'none'; }

  // ── Toggle discount/tax/hsn columns visibility based on company ──
  function showAllColumns() {
    toggleColumns(true, true, true);
  }

  function toggleColumns(showDiscount, showTax, showHsn) {
    const table = document.getElementById('lines-table');
    if (!table) return;

    // Add/remove classes for visibility
    if (showDiscount) {
      table.classList.remove('hide-discount');
    } else {
      table.classList.add('hide-discount');
    }

    if (showTax) {
      table.classList.remove('hide-tax');
    } else {
      table.classList.add('hide-tax');
    }

    if (showHsn) {
      table.classList.remove('hide-hsn');
    } else {
      table.classList.add('hide-hsn');
    }
  }

  // Populate line items from existing invoice
  @if(isset($invoice) && $invoice->items->count() > 0)
    @foreach($invoice->items as $item)
      addRow({
        item_id: {{ $item->item_id ?? 'null' }},
        description: "{{ addslashes($item->description) }}",
        hsn: "{{ addslashes($item->hsn_code ?? '') }}",
        qty: {{ $item->quantity }},
        unit: "{{ addslashes($item->unit) }}",
        rate: {{ $item->rate }},
        disc: {{ $item->discount_percent ?? 0 }},
        taxRule: {{ $item->tax_rule_id ?? 'null' }}
      });
    @endforeach
  @else
    // Start with one empty row for new invoice
    addRow();
  @endif

  // Initialize column visibility on page load
  const companySel = document.getElementById('company_id');
  if (companySel.value) {
    const opt = companySel.options[companySel.selectedIndex];
    const showDiscount = opt.dataset.showDiscount === '1';
    const showTax = opt.dataset.showTax === '1';
    const showHsn = opt.dataset.showHsn === '1';
    toggleColumns(showDiscount, showTax, showHsn);
  }

  // Recalculate totals after populating rows
  recalcAll();
})();
</script>
@endpush
