@extends('adminlte::page')
@section('title', 'New Item')
@section('content_header')<h1>New Item</h1>@endsection
@section('content')
<div class="card" style="max-width:700px">
    <form action="{{ route('admin.items.store') }}" method="POST">
    @csrf
    <div class="card-body">
        @if($errors->any())<x-adminlte-alert theme="danger" dismissable><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-adminlte-alert>@endif
        <div class="form-group"><label>Name *</label><input name="name" class="form-control" value="{{ old('name') }}" required></div>
        <div class="form-group"><label>Description</label><textarea name="description" class="form-control" rows="2">{{ old('description') }}</textarea></div>
        <div class="row">
            <div class="col-md-4"><div class="form-group"><label>HSN Code</label><input name="hsn_code" class="form-control" value="{{ old('hsn_code') }}"></div></div>
            <div class="col-md-4"><div class="form-group"><label>Unit *</label>
                <select name="unit" class="form-control" required>
                    @foreach(['Nos','Pcs','Kg','Litre','Metre','Box','Set','Pair','Sqft'] as $u)
                        <option value="{{ $u }}" {{ old('unit') === $u ? 'selected' : '' }}>{{ $u }}</option>
                    @endforeach
                </select>
            </div></div>
            <div class="col-md-4"><div class="form-group"><label>Category</label><input name="category" class="form-control" value="{{ old('category') }}"></div></div>
        </div>
        <div class="row">
            <div class="col-md-6"><div class="form-group"><label>Selling Price (₹) *</label><input name="selling_price" type="number" step="0.01" class="form-control" value="{{ old('selling_price') }}" required></div></div>
            <div class="col-md-6"><div class="form-group"><label>Tax Rule *</label>
                <select name="tax_rule_id" class="form-control" required>
                    <option value="">-- Select --</option>
                    @foreach($taxRules as $t)
                        <option value="{{ $t->id }}" {{ old('tax_rule_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                    @endforeach
                </select>
            </div></div>
        </div>
        <div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked><label class="custom-control-label" for="is_active">Active</label></div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save Item</button>
        <a href="{{ route('admin.items.index') }}" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
@endsection
