<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>{{ $invoice->invoice_number }}</title>
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

@unless($isPdf)
<div id="toolbar">
    <button class="btn-print" onclick="window.print()">&#128438; Print</button>
    <a class="btn-pdf" href="{{ route('admin.invoices.pdf', $invoice) }}">&#11015; Download PDF</a>
    <a class="btn-back" href="{{ route('admin.invoices.show', $invoice) }}">&larr; Back</a>
</div>
@endunless

@php
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

  $hasDiscount = $invoice->company->show_discount && $invoice->items->sum('discount_amount') > 0;
  $hasCgst     = $invoice->company->show_tax && $invoice->cgst_total > 0;
  $hasIgst     = $invoice->company->show_tax && $invoice->igst_total > 0;

  // Number of columns in items table
  $extraCols = ($hasDiscount ? 1 : 0) + ($hasCgst ? 2 : 0) + ($hasIgst ? 1 : 0);
  $totalCols = 7 + $extraCols; // #, desc, hsn, qty, unit, rate, [disc], [cgst,sgst], [igst], amount
@endphp

@foreach($copies as $ci => $copy)

  @if($ci > 0)
    @if($isPdf)
      {{-- PDF: each copy on its own page --}}
      <div style="page-break-after: always;"></div>
    @else
      <div class="separator">&#9988; &nbsp; CUT HERE &nbsp; &#9988;</div>
    @endif
  @endif

  <div class="copy">

    {{-- Copy label --}}
    <div class="copy-label">{{ $copy['label'] }}</div>

    {{-- ── Header ── --}}
    <table class="header-table" style="border-bottom: 2px solid #2c3e50; padding-bottom: 6px; margin-bottom: 8px;">
      <tr>
        <td style="vertical-align: top;">
          <div class="company-name">{{ $invoice->company->name }}</div>
          <div class="company-sub">
            {{ $invoice->company->address }},
            {{ $invoice->company->city }},
            {{ $invoice->company->state }} &ndash; {{ $invoice->company->pincode }}<br>
            @if($invoice->company->phone)Ph: {{ $invoice->company->phone }}@endif
            @if($invoice->company->gstin) &nbsp;|&nbsp; GSTIN: {{ $invoice->company->gstin }}@endif
          </div>
        </td>
        <td style="vertical-align: top; text-align: right; white-space: nowrap; padding-left: 10px;">
          <div class="inv-title">TAX INVOICE</div>
          <div class="inv-meta">
            <strong>{{ $invoice->invoice_number }}</strong><br>
            Date: {{ $invoice->invoice_date?->format('d M Y') }}<br>
            @if($invoice->due_date)Due: {{ $invoice->due_date->format('d M Y') }}<br>@endif
            <span class="badge {{ $statusBadge }}">{{ $invoice->status }}</span>
            &nbsp; FY {{ $invoice->financial_year }}
          </div>
        </td>
      </tr>
    </table>

    {{-- ── Addresses ── --}}
    <table class="addr-table" cellspacing="4">
      <tr>
        <td class="addr-box">
          <div class="addr-label">Bill From</div>
          <div class="addr-name">{{ $invoice->company->name }}</div>
          <div class="addr-line">{{ $invoice->company->address }}</div>
          <div class="addr-line">{{ $invoice->company->city }}, {{ $invoice->company->state }} &ndash; {{ $invoice->company->pincode }}</div>
          @if($invoice->company->gstin)<div class="addr-line">GSTIN: {{ $invoice->company->gstin }}</div>@endif
        </td>
        <td style="width: 2%;"></td>
        <td class="addr-box">
          <div class="addr-label">Bill To</div>
          <div class="addr-name">{{ $invoice->client->name }}</div>
          <div class="addr-line">{{ $invoice->client->billing_address }}</div>
          <div class="addr-line">{{ $invoice->client->billing_city }}, {{ $invoice->client->billing_state }} &ndash; {{ $invoice->client->billing_pincode }}</div>
          @if($invoice->client->phone)<div class="addr-line">Ph: {{ $invoice->client->phone }}</div>@endif
          @if($invoice->client->gstin)<div class="addr-line">GSTIN: {{ $invoice->client->gstin }}</div>@endif
        </td>
      </tr>
    </table>

    {{-- ── Line items ── --}}
    <table class="items">
      <thead>
        <tr>
          <th style="width:22px;">#</th>
          <th>Description</th>
          <th style="width:40px;">HSN</th>
          <th class="r" style="width:46px;">Qty</th>
          <th style="width:30px;">Unit</th>
          <th class="r" style="width:60px;">Rate (&#8377;)</th>
          @if($hasDiscount)<th class="r" style="width:36px;">Disc.</th>@endif
          @if($hasCgst)
            <th class="r" style="width:36px;">CGST</th>
            <th class="r" style="width:36px;">SGST</th>
          @endif
          @if($hasIgst)<th class="r" style="width:36px;">IGST</th>@endif
          <th class="r" style="width:70px;">Amount (&#8377;)</th>
        </tr>
      </thead>
      <tbody>
        @foreach($invoice->items->sortBy('sort_order') as $i => $line)
        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ $line->description }}</td>
          <td>{{ $line->hsn_code ?? '&mdash;' }}</td>
          <td class="r">{{ rtrim(rtrim(number_format($line->quantity, 3), '0'), '.') }}</td>
          <td>{{ $line->unit }}</td>
          <td class="r">{{ number_format($line->rate, 2) }}</td>
          @if($hasDiscount)
            <td class="r">{{ $line->discount_percent > 0 ? $line->discount_percent.'%' : '&mdash;' }}</td>
          @endif
          @if($hasCgst)
            <td class="r">{{ $line->cgst_rate > 0 ? $line->cgst_rate.'%' : '&mdash;' }}</td>
            <td class="r">{{ $line->sgst_rate > 0 ? $line->sgst_rate.'%' : '&mdash;' }}</td>
          @endif
          @if($hasIgst)
            <td class="r">{{ $line->igst_rate > 0 ? $line->igst_rate.'%' : '&mdash;' }}</td>
          @endif
          <td class="r">{{ number_format($line->total, 2) }}</td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        @if($invoice->company->show_discount && $invoice->discount_amount > 0)
        <tr>
          <td colspan="{{ $totalCols - 1 }}" class="r">Subtotal</td>
          <td class="r">{{ number_format($invoice->subtotal, 2) }}</td>
        </tr>
        <tr>
          <td colspan="{{ $totalCols - 1 }}" class="r">Discount</td>
          <td class="r">- {{ number_format($invoice->discount_amount, 2) }}</td>
        </tr>
        @endif
        @if($invoice->company->show_tax)
          @if($hasCgst)
          <tr>
            <td colspan="{{ $totalCols - 1 }}" class="r">CGST</td>
            <td class="r">{{ number_format($invoice->cgst_total, 2) }}</td>
          </tr>
          <tr>
            <td colspan="{{ $totalCols - 1 }}" class="r">SGST</td>
            <td class="r">{{ number_format($invoice->sgst_total, 2) }}</td>
          </tr>
          @endif
          @if($hasIgst)
          <tr>
            <td colspan="{{ $totalCols - 1 }}" class="r">IGST</td>
            <td class="r">{{ number_format($invoice->igst_total, 2) }}</td>
          </tr>
          @endif
        @endif
        <tr class="row-grand">
          <td colspan="{{ $totalCols - 1 }}" class="r">GRAND TOTAL</td>
          <td class="r">&#8377; {{ number_format($invoice->grand_total, 2) }}</td>
        </tr>
      </tfoot>
    </table>

    {{-- ── Payment summary ── --}}
    @if($invoice->amount_paid > 0 || $invoice->balance_due > 0)
    <table style="width:100%;"><tr><td style="text-align:right;">
      <table class="pay-table" style="display:inline-table;">
        <tr>
          <td>Grand Total</td>
          <td class="r">&#8377; {{ number_format($invoice->grand_total, 2) }}</td>
        </tr>
        <tr>
          <td>Amount Received</td>
          <td class="r">&#8377; {{ number_format($invoice->amount_paid, 2) }}</td>
        </tr>
        @if($invoice->balance_due > 0)
        <tr class="pay-balance">
          <td>Balance Due</td>
          <td class="r">&#8377; {{ number_format($invoice->balance_due, 2) }}</td>
        </tr>
        @else
        <tr class="pay-full">
          <td colspan="2">&#10003; PAID IN FULL</td>
        </tr>
        @endif
      </table>
    </td></tr></table>
    @endif

    {{-- ── Notes & Terms ── --}}
    @if($invoice->notes || $invoice->terms)
    <table class="nt-table">
      <tr>
        @if($invoice->notes)
        <td class="nt-cell">
          <div class="nt-label">Notes</div>
          {{ $invoice->notes }}
        </td>
        @endif
        @if($invoice->terms)
        <td class="nt-cell">
          <div class="nt-label">Terms &amp; Conditions</div>
          {{ $invoice->terms }}
        </td>
        @endif
      </tr>
    </table>
    @endif

    {{-- ── Signatures ── --}}
    <table class="sig-table">
      <tr>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div>Received By (Client)</div>
        </td>
        <td class="sig-cell">
          <div class="sig-line"></div>
          <div>Authorised Signatory</div>
          <div class="sig-sub">{{ $invoice->company->name }}</div>
        </td>
      </tr>
    </table>

  </div>{{-- .copy --}}

@endforeach

</body>
</html>
