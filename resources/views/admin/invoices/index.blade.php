@extends('adminlte::page')
@section('title', 'Invoices')
@section('content_header')
<div class="d-flex justify-content-between align-items-center">
    <h1>Invoices</h1>
    <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> New Invoice
    </a>
</div>
@endsection
@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif

<div class="card">
    <div class="card-header">
        <form method="GET" class="form-inline">
            <input name="search" class="form-control form-control-sm mr-2" placeholder="Invoice #" value="{{ request('search') }}">
            <select name="status" class="form-control form-control-sm mr-2">
                <option value="">All Status</option>
                @foreach(['draft','sent','paid','cancelled'] as $s)
                    <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="form-control form-control-sm mr-2">
                <option value="">All Payment</option>
                @foreach(['unpaid','partial','paid'] as $s)
                    <option value="{{ $s }}" {{ request('payment_status') === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                @endforeach
            </select>
            <button class="btn btn-sm btn-primary mr-2">Filter</button>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-default">Clear</a>
        </form>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Invoice #</th><th>Date</th><th>Client</th><th>Company</th><th>Amount</th><th>Status</th><th>Payment</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($invoices as $inv)
                @php $colors = ['draft'=>'secondary','sent'=>'info','paid'=>'success','cancelled'=>'danger']; @endphp
                <tr>
                    <td>{{ $inv->invoice_number }}</td>
                    <td>{{ $inv->invoice_date?->format('d M Y') }}</td>
                    <td>{{ $inv->client->name ?? '—' }}</td>
                    <td>{{ $inv->company->name ?? '—' }}</td>
                    <td>₹{{ number_format($inv->grand_total, 2) }}</td>
                    <td><span class="badge badge-{{ $colors[$inv->status] ?? 'secondary' }}">{{ ucfirst($inv->status) }}</span></td>
                    <td>
                        <span class="badge badge-{{ $inv->payment_status === 'paid' ? 'success' : ($inv->payment_status === 'partial' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($inv->payment_status) }}
                        </span>
                    </td>
                    <td>
                        <a href="{{ route('admin.invoices.show', $inv) }}" class="btn btn-xs btn-default">View</a>
                        @if($inv->status === 'draft')
                            <form action="{{ route('admin.invoices.send', $inv) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn btn-xs btn-info">Send</button>
                            </form>
                        @endif
                        @if(!in_array($inv->status, ['paid','cancelled']))
                            <form action="{{ route('admin.invoices.cancel', $inv) }}" method="POST" class="d-inline" onsubmit="return confirm('Cancel invoice?')">
                                @csrf @method('PATCH')
                                <button class="btn btn-xs btn-warning">Cancel</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">No invoices found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $invoices->links() }}</div>
</div>
@endsection
