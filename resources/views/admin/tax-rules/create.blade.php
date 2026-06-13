@extends('adminlte::page')
@section('title', 'New Tax Rule')
@section('content_header')<h1>New Tax Rule</h1>@endsection
@section('content')
<div class="card" style="max-width:600px">
    <form action="{{ route('admin.tax-rules.store') }}" method="POST">
    @csrf
    <div class="card-body">
        @if($errors->any())<x-adminlte-alert theme="danger" dismissable><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-adminlte-alert>@endif
        <div class="form-group"><label>Name *</label><input name="name" class="form-control" value="{{ old('name') }}" placeholder="e.g. GST 18%" required></div>
        <div class="form-group"><label>Type *</label>
            <select name="type" class="form-control" required>
                <option value="gst"    {{ old('type') === 'gst'    ? 'selected' : '' }}>GST (Intra-state)</option>
                <option value="igst"   {{ old('type') === 'igst'   ? 'selected' : '' }}>IGST (Inter-state)</option>
                <option value="exempt" {{ old('type') === 'exempt' ? 'selected' : '' }}>Exempt</option>
            </select>
        </div>
        <div class="row">
            <div class="col-4"><div class="form-group"><label>CGST %</label><input name="cgst_rate" type="number" step="0.01" class="form-control" value="{{ old('cgst_rate', 0) }}"></div></div>
            <div class="col-4"><div class="form-group"><label>SGST %</label><input name="sgst_rate" type="number" step="0.01" class="form-control" value="{{ old('sgst_rate', 0) }}"></div></div>
            <div class="col-4"><div class="form-group"><label>IGST %</label><input name="igst_rate" type="number" step="0.01" class="form-control" value="{{ old('igst_rate', 0) }}"></div></div>
        </div>
        <div class="form-group"><label>Description</label><input name="description" class="form-control" value="{{ old('description') }}"></div>
        <div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" checked><label class="custom-control-label" for="is_active">Active</label></div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="{{ route('admin.tax-rules.index') }}" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
@endsection
