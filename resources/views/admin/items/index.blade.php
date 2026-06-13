@extends('adminlte::page')
@section('title', 'Items')
@section('content_header')
    <h1>Items / Products <a href="{{ route('admin.items.create') }}" class="btn btn-primary btn-sm ml-2">+ New</a></h1>
@endsection
@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Name</th><th>HSN</th><th>Unit</th><th>Price</th><th>Tax Rule</th><th>Category</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->hsn_code ?? '—' }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>₹{{ number_format($item->selling_price, 2) }}</td>
                    <td>{{ $item->taxRule->name ?? '—' }}</td>
                    <td>{{ $item->category ?? '—' }}</td>
                    <td><span class="badge badge-{{ $item->is_active ? 'success' : 'secondary' }}">{{ $item->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.items.edit', $item) }}" class="btn btn-xs btn-warning">Edit</a>
                        <form action="{{ route('admin.items.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">No items found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $items->links() }}</div>
</div>
@endsection
