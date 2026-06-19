@extends('adminlte::page')
@section('title', 'Invoice ' . $invoice->invoice_number)
@section('content_header')
    <h1>Invoice: {{ $invoice->invoice_number }}
        @php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','cancelled'=>'danger']; @endphp
        <span class="badge badge-{{ $colors[$invoice->status] ?? 'secondary' }} ml-2">{{ ucfirst($invoice->status) }}</span>
    </h1>
@endsection
@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Invoice Details</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" class="btn btn-sm btn-secondary">
                        <i class="fas fa-print"></i> Print
                    </a>
                    <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-sm btn-danger">
                        <i class="fas fa-file-pdf"></i> PDF
                    </a>
                    @if($invoice->status === 'draft')
                        <form action="{{ route('admin.invoices.send', $invoice) }}" method="POST" class="d-inline">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-info">Mark Sent</button>
                        </form>
                    @endif
                    @if(!in_array($invoice->status, ['paid','cancelled']))
                        <form action="{{ route('admin.invoices.cancel', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel this invoice?')">
                            @csrf @method('PATCH')
                            <button class="btn btn-sm btn-warning">Cancel</button>
                        </form>
                    @endif
                    @if($invoice->status !== 'paid')
                        <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete permanently?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Company</strong><br>
                        {{ $invoice->company->name ?? '—' }}<br>
                        {{ $invoice->company->address ?? '' }}, {{ $invoice->company->city ?? '' }}<br>
                        GSTIN: {{ $invoice->company->gstin ?? '—' }}
                    </div>
                    <div class="col-md-6">
                        <strong>Client</strong><br>
                        {{ $invoice->client->name ?? '—' }}<br>
                        {{ $invoice->client->billing_address ?? '' }}, {{ $invoice->client->billing_city ?? '' }}<br>
                        GSTIN: {{ $invoice->client->gstin ?? '—' }}
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4"><strong>Invoice Date:</strong> {{ $invoice->invoice_date?->format('d M Y') }}</div>
                    <div class="col-md-4"><strong>Due Date:</strong> {{ $invoice->due_date?->format('d M Y') }}</div>
                    <div class="col-md-4"><strong>Supply Type:</strong> {{ ucfirst($invoice->supply_type ?? '—') }}</div>
                </div>
                <hr>
                <table class="table table-sm table-bordered mt-3">
                    <thead class="thead-light">
                        <tr>
                            <th>#</th><th>Item</th><th>HSN</th><th>Qty</th><th>Unit</th><th>Rate</th>
                            @if($invoice->company->show_discount)<th>Disc.</th>@endif
                            @if($invoice->company->show_tax)<th>Tax</th>@endif
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->items as $i => $line)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $line->item->name ?? $line->description }}</td>
                            <td>{{ $line->hsn_code ?? '—' }}</td>
                            <td>{{ $line->quantity }}</td>
                            <td>{{ $line->unit }}</td>
                            <td>₹{{ number_format($line->rate, 2) }}</td>
                            @if($invoice->company->show_discount)<td>{{ $line->discount_percent ?? 0 }}%</td>@endif
                            @if($invoice->company->show_tax)<td>{{ $line->item->taxRule->name ?? '—' }}</td>@endif
                            <td>₹{{ number_format($line->total, 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        @php
                            $colspanCount = 6;
                            if($invoice->company->show_discount) $colspanCount++;
                            if($invoice->company->show_tax) $colspanCount++;
                        @endphp
                        <tr><td colspan="{{ $colspanCount }}" class="text-right"><strong>Subtotal</strong></td><td>₹{{ number_format($invoice->subtotal, 2) }}</td></tr>
                        @if($invoice->company->show_discount && $invoice->discount_amount > 0)
                        <tr><td colspan="{{ $colspanCount }}" class="text-right">Discount</td><td>-₹{{ number_format($invoice->discount_amount, 2) }}</td></tr>
                        @endif
                        <tr><td colspan="{{ $colspanCount }}" class="text-right">Taxable Amount</td><td>₹{{ number_format($invoice->taxable_amount, 2) }}</td></tr>
                        @if($invoice->company->show_tax)
                            @if($invoice->cgst_total > 0)
                            <tr><td colspan="{{ $colspanCount }}" class="text-right">CGST</td><td>₹{{ number_format($invoice->cgst_total, 2) }}</td></tr>
                            <tr><td colspan="{{ $colspanCount }}" class="text-right">SGST</td><td>₹{{ number_format($invoice->sgst_total, 2) }}</td></tr>
                            @endif
                            @if($invoice->igst_total > 0)
                            <tr><td colspan="{{ $colspanCount }}" class="text-right">IGST</td><td>₹{{ number_format($invoice->igst_total, 2) }}</td></tr>
                            @endif
                        @endif
                        <tr class="table-active"><td colspan="{{ $colspanCount }}" class="text-right"><strong>Grand Total</strong></td><td><strong>₹{{ number_format($invoice->grand_total, 2) }}</strong></td></tr>
                    </tfoot>
                </table>
                @if($invoice->notes)
                    <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">

        {{-- Payment summary card --}}
        <div class="card">
            <div class="card-header"><h3 class="card-title">Payment Summary</h3></div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <tr><td>Grand Total</td><td class="text-right font-weight-bold">₹{{ number_format($invoice->grand_total, 2) }}</td></tr>
                    <tr><td>Amount Paid</td><td class="text-right text-success font-weight-bold">₹{{ number_format($invoice->amount_paid, 2) }}</td></tr>
                    <tr class="{{ $invoice->balance_due > 0 ? 'table-danger' : 'table-success' }}">
                        <td>Balance Due</td>
                        <td class="text-right font-weight-bold">₹{{ number_format($invoice->balance_due, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        {{-- Record payment form --}}
        @if($invoice->balance_due > 0 && $invoice->status !== 'cancelled')
        <div class="card card-success">
            <div class="card-header"><h3 class="card-title">Record Payment</h3></div>
            <div class="card-body">
                <form action="{{ route('admin.invoices.payment', $invoice) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Amount (₹) <span class="text-danger">*</span></label>
                        <input name="amount" type="number" step="0.01" min="0.01"
                               max="{{ $invoice->balance_due }}"
                               class="form-control @error('amount') is-invalid @enderror"
                               placeholder="0.00" value="{{ old('amount') }}" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Payment Date <span class="text-danger">*</span></label>
                        <input name="payment_date" type="date"
                               class="form-control @error('payment_date') is-invalid @enderror"
                               value="{{ old('payment_date', date('Y-m-d')) }}" required>
                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-control @error('payment_method') is-invalid @enderror" required>
                            <option value="cash"          {{ old('payment_method','cash') === 'cash'          ? 'selected' : '' }}>Cash</option>
                            <option value="cheque"        {{ old('payment_method') === 'cheque'        ? 'selected' : '' }}>Cheque</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer / NEFT</option>
                            <option value="upi"           {{ old('payment_method') === 'upi'           ? 'selected' : '' }}>UPI</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>Reference / Cheque No. / UTR</label>
                        <input name="reference" type="text" class="form-control"
                               placeholder="Optional" value="{{ old('reference') }}" maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>Note</label>
                        <textarea name="note" class="form-control" rows="2"
                                  placeholder="Optional note">{{ old('note') }}</textarea>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Record Payment
                    </button>
                </form>
            </div>
        </div>
        @endif

        {{-- Payment log --}}
        @if($invoice->paymentLogs->isNotEmpty())
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Payment History</h3>
                <div class="card-tools">
                    <span class="badge badge-info">{{ $invoice->paymentLogs->count() }} entries</span>
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
                    @foreach($invoice->paymentLogs as $log)
                        <tr>
                            <td>
                                {{ $log->payment_date->format('d M Y') }}
                                @if($log->reference)
                                    <br><small class="text-muted">{{ $log->reference }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                  $methodLabels = ['cash'=>'Cash','cheque'=>'Cheque','bank_transfer'=>'Bank Transfer','upi'=>'UPI'];
                                @endphp
                                <span class="badge badge-secondary">{{ $methodLabels[$log->payment_method] ?? $log->payment_method }}</span>
                                @if($log->note)
                                    <br><small class="text-muted">{{ $log->note }}</small>
                                @endif
                            </td>
                            <td class="text-right text-success font-weight-bold">
                                ₹{{ number_format($log->amount, 2) }}
                            </td>
                            <td class="text-right {{ $log->balance_after > 0 ? 'text-danger' : 'text-success' }}">
                                ₹{{ number_format($log->balance_after, 2) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <a href="{{ route('admin.invoices.index') }}" class="btn btn-default btn-block mt-2">← Back to Invoices</a>
    </div>
</div>
@endsection
