@extends('adminlte::page')
@section('title', 'Clients')
@section('content_header')
    <h1>Clients <a href="{{ route('admin.clients.create') }}" class="btn btn-primary btn-sm ml-2">+ New</a></h1>
@endsection
@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Name</th><th>Type</th><th>Email</th><th>Phone</th><th>GSTIN</th><th>City</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($clients as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td><span class="badge badge-{{ $c->client_type === 'business' ? 'primary' : 'secondary' }}">{{ ucfirst($c->client_type) }}</span></td>
                    <td>{{ $c->email ?? '—' }}</td>
                    <td>{{ $c->phone ?? '—' }}</td>
                    <td>{{ $c->gstin ?? '—' }}</td>
                    <td>{{ $c->billing_city }}</td>
                    <td><span class="badge badge-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.clients.edit', $c) }}" class="btn btn-xs btn-warning">Edit</a>
                        <form action="{{ route('admin.clients.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted">No clients found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $clients->links() }}</div>
</div>
@endsection
