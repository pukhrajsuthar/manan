@extends('adminlte::page')
@section('title', 'Companies')
@section('content_header')
    <h1>Companies <a href="{{ route('admin.companies.create') }}" class="btn btn-primary btn-sm ml-2">+ New</a></h1>
@endsection
@section('content')
@if(session('success'))
    <x-adminlte-alert theme="success" dismissable>{{ session('success') }}</x-adminlte-alert>
@endif
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped table-hover">
            <thead><tr><th>Name</th><th>GSTIN</th><th>City</th><th>Phone</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
            @forelse($companies as $c)
                <tr>
                    <td>{{ $c->name }}</td>
                    <td>{{ $c->gstin ?? '—' }}</td>
                    <td>{{ $c->city }}, {{ $c->state }}</td>
                    <td>{{ $c->phone ?? '—' }}</td>
                    <td><span class="badge badge-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.companies.edit', $c) }}" class="btn btn-xs btn-warning">Edit</a>
                        <form action="{{ route('admin.companies.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted">No companies found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $companies->links() }}</div>
</div>
@endsection
