@extends('adminlte::page')
@section('title', 'Add Category')
@section('content_header')
    <h1>Add Category</h1>
@endsection
@section('content')
<div class="card">
    <form action="{{ route('admin.categories.store') }}" method="POST">
    @csrf
    <div class="card-body">
        @if($errors->any())
            <x-adminlte-alert theme="danger" dismissable>
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </x-adminlte-alert>
        @endif
        <div class="form-group">
            <label>Category Name <span class="text-danger">*</span></label>
            <input name="name" class="form-control @error('name') is-invalid @enderror"
                   placeholder="e.g. Plywood, Cement Sheet, Hardware" value="{{ old('name') }}" required>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label>Display Order</label>
            <input name="order" type="number" class="form-control @error('order') is-invalid @enderror"
                   placeholder="0" value="{{ old('order', 0) }}">
            @error('order')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="custom-control custom-switch">
            <input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1"
                   {{ old('is_active', true) ? 'checked' : '' }}>
            <label class="custom-control-label" for="is_active">Active</label>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Create Category</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
@endsection
