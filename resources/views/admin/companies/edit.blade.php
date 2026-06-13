@extends('adminlte::page')
@section('title', 'Edit Company')
@section('content_header')
    <h1>Edit Company: {{ $company->name }}</h1>
@endsection
@section('content')
<div class="card">
    <form action="{{ route('admin.companies.update', $company) }}" method="POST">
    @csrf @method('PUT')
    <div class="card-body">
        @if($errors->any())
            <x-adminlte-alert theme="danger" dismissable>
                <ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
            </x-adminlte-alert>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="form-group"><label>Company Name *</label><input name="name" class="form-control" value="{{ old('name', $company->name) }}" required></div>
                <div class="form-group"><label>GSTIN</label><input name="gstin" class="form-control" value="{{ old('gstin', $company->gstin) }}"></div>
                <div class="form-group"><label>PAN</label><input name="pan" class="form-control" value="{{ old('pan', $company->pan) }}"></div>
                <div class="form-group"><label>Email</label><input name="email" type="email" class="form-control" value="{{ old('email', $company->email) }}"></div>
                <div class="form-group"><label>Phone</label><input name="phone" class="form-control" value="{{ old('phone', $company->phone) }}"></div>
                <div class="form-group"><label>Website</label><input name="website" class="form-control" value="{{ old('website', $company->website) }}"></div>
            </div>
            <div class="col-md-6">
                <div class="form-group"><label>Address *</label><textarea name="address" class="form-control" rows="3" required>{{ old('address', $company->address) }}</textarea></div>
                <div class="row">
                    <div class="col-md-6"><div class="form-group"><label>City *</label><input name="city" class="form-control" value="{{ old('city', $company->city) }}" required></div></div>
                    <div class="col-md-6"><div class="form-group"><label>Pincode *</label><input name="pincode" class="form-control" value="{{ old('pincode', $company->pincode) }}" required></div></div>
                </div>
                <div class="row">
                    <div class="col-md-8"><div class="form-group"><label>State *</label><input name="state" class="form-control" value="{{ old('state', $company->state) }}" required></div></div>
                    <div class="col-md-4"><div class="form-group"><label>State Code *</label><input name="state_code" class="form-control" value="{{ old('state_code', $company->state_code) }}" required></div></div>
                </div>
            </div>
        </div>
        <hr><h5>Bank Details</h5>
        <div class="row">
            <div class="col-md-3"><div class="form-group"><label>Bank Name</label><input name="bank_name" class="form-control" value="{{ old('bank_name', $company->bank_name) }}"></div></div>
            <div class="col-md-3"><div class="form-group"><label>Account Number</label><input name="bank_account_number" class="form-control" value="{{ old('bank_account_number', $company->bank_account_number) }}"></div></div>
            <div class="col-md-3"><div class="form-group"><label>IFSC</label><input name="bank_ifsc" class="form-control" value="{{ old('bank_ifsc', $company->bank_ifsc) }}"></div></div>
            <div class="col-md-3"><div class="form-group"><label>Branch</label><input name="bank_branch" class="form-control" value="{{ old('bank_branch', $company->bank_branch) }}"></div></div>
        </div>
        <hr><h5>Invoice Settings</h5>
        <div class="row">
            <div class="col-md-3"><div class="form-group"><label>Invoice Prefix *</label><input name="invoice_prefix" class="form-control" value="{{ old('invoice_prefix', $company->invoice_prefix) }}" required></div></div>
            <div class="col-md-3"><div class="form-group"><label>Financial Year *</label><input name="financial_year" class="form-control" value="{{ old('financial_year', $company->financial_year) }}" required></div></div>
            <div class="col-md-3"><div class="form-group"><label>Currency *</label><input name="currency" class="form-control" value="{{ old('currency', $company->currency) }}" required></div></div>
            <div class="col-md-3"><div class="form-group"><label>Status</label><div class="custom-control custom-switch mt-2"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $company->is_active) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div></div></div>
        </div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update Company</button>
        <a href="{{ route('admin.companies.index') }}" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
@endsection
