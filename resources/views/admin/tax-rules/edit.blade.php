@extends('adminlte::page')
@section('title', 'Edit Tax Rule')
@section('content_header')<h1>Edit Tax Rule: {{ $taxRule->name }}</h1>@endsection
@section('content')
<div class="card" style="max-width:600px">
    <form action="{{ route('admin.tax-rules.update', $taxRule) }}" method="POST">
    @csrf @method('PUT')
    <div class="card-body">
        @if($errors->any())<x-adminlte-alert theme="danger" dismissable><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-adminlte-alert>@endif
        <div class="form-group"><label>Name *</label><input name="name" class="form-control" value="{{ old('name', $taxRule->name) }}" required></div>
        <div class="form-group"><label>Type *</label>
            <select name="type" class="form-control" required>
                <option value="gst"    {{ old('type', $taxRule->type) === 'gst'    ? 'selected' : '' }}>GST (Intra-state)</option>
                <option value="igst"   {{ old('type', $taxRule->type) === 'igst'   ? 'selected' : '' }}>IGST (Inter-state)</option>
                <option value="exempt" {{ old('type', $taxRule->type) === 'exempt' ? 'selected' : '' }}>Exempt</option>
            </select>
        </div>
        <div class="row">
            <div class="col-4"><div class="form-group"><label>CGST %</label><input name="cgst_rate" type="number" step="0.01" class="form-control" value="{{ old('cgst_rate', $taxRule->cgst_rate) }}"></div></div>
            <div class="col-4"><div class="form-group"><label>SGST %</label><input name="sgst_rate" type="number" step="0.01" class="form-control" value="{{ old('sgst_rate', $taxRule->sgst_rate) }}"></div></div>
            <div class="col-4"><div class="form-group"><label>IGST %</label><input name="igst_rate" type="number" step="0.01" class="form-control" value="{{ old('igst_rate', $taxRule->igst_rate) }}"></div></div>
        </div>
        <div class="form-group"><label>Description</label><input name="description" class="form-control" value="{{ old('description', $taxRule->description) }}"></div>
        <div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $taxRule->is_active) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('admin.tax-rules.index') }}" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
@endsection
