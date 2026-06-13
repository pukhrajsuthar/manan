@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard <small class="text-muted">{{ $fy }}</small></h1>
@endsection

@section('content')

@if(session('success'))
    <x-adminlte-alert theme="success" title="Success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif

<div class="row">
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="{{ number_format($stats['total_invoices']) }}" text="Total Invoices" icon="fas fa-file-invoice" theme="primary" url="{{ route('admin.invoices.index') }}" url-text="View all"/>
    </div>
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="₹{{ number_format($stats['total_revenue'], 2) }}" text="Total Revenue" icon="fas fa-rupee-sign" theme="success"/>
    </div>
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="₹{{ number_format($stats['total_outstanding'], 2) }}" text="Outstanding" icon="fas fa-clock" theme="warning"/>
    </div>
    <div class="col-lg-3 col-6">
        <x-adminlte-small-box title="{{ $stats['overdue_count'] }}" text="Overdue" icon="fas fa-exclamation-circle" theme="danger"/>
    </div>
</div>

<div class="row">
    <div class="col-lg-4 col-6">
        <x-adminlte-small-box title="{{ $stats['draft_count'] }}" text="Draft" icon="fas fa-pencil-alt" theme="secondary" url="{{ route('admin.invoices.index', ['status' => 'draft']) }}" url-text="View"/>
    </div>
    <div class="col-lg-4 col-6">
        <x-adminlte-small-box title="{{ $stats['sent_count'] }}" text="Sent" icon="fas fa-paper-plane" theme="info" url="{{ route('admin.invoices.index', ['status' => 'sent']) }}" url-text="View"/>
    </div>
    <div class="col-lg-4 col-6">
        <x-adminlte-small-box title="{{ $stats['paid_count'] }}" text="Paid" icon="fas fa-check-circle" theme="success" url="{{ route('admin.invoices.index', ['status' => 'paid']) }}" url-text="View"/>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Invoices</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary">View All</a>
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
                    @forelse($recent_invoices as $invoice)
                        <tr>
                            <td>{{ $invoice->invoice_number }}</td>
                            <td>{{ $invoice->client->name ?? '—' }}</td>
                            <td>{{ $invoice->company->name ?? '—' }}</td>
                            <td>{{ $invoice->invoice_date?->format('d M Y') }}</td>
                            <td>₹{{ number_format($invoice->grand_total, 2) }}</td>
                            <td>
                                @php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','cancelled'=>'danger']; @endphp
                                <span class="badge badge-{{ $colors[$invoice->status] ?? 'secondary' }}">{{ ucfirst($invoice->status) }}</span>
                            </td>
                            <td><a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-xs btn-default">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">No invoices yet.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
