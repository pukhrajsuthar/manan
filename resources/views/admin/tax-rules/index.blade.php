@extends('adminlte::page')
@section('title', 'Tax Rules')
@section('content_header')
    <h1>Tax Rules <a href="{{ route('admin.tax-rules.create') }}" class="btn btn-primary btn-sm ml-2">+ New</a></h1>
@endsection
@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Name</th><th>Type</th><th>CGST %</th><th>SGST %</th><th>IGST %</th><th>Description</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($taxRules as $t)
                <tr>
                    <td>{{ $t->name }}</td>
                    <td><span class="badge badge-info">{{ strtoupper($t->type) }}</span></td>
                    <td>{{ $t->cgst_rate }}%</td>
                    <td>{{ $t->sgst_rate }}%</td>
                    <td>{{ $t->igst_rate }}%</td>
                    <td>{{ $t->description ?? '—' }}</td>
                    <td><span class="badge badge-{{ $t->is_active ? 'success' : 'secondary' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.tax-rules.edit', $t) }}" class="btn btn-xs btn-warning">Edit</a>
                        <form action="{{ route('admin.tax-rules.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">No tax rules found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $taxRules->links() }}</div>
</div>
@endsection
