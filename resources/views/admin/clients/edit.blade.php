@extends('adminlte::page')
@section('title', 'Edit Client')
@section('content_header')<h1>Edit Client: {{ $client->name }}</h1>@endsection
@section('content')
<div class="card">
    <form action="{{ route('admin.clients.update', $client) }}" method="POST">
    @csrf @method('PUT')
    <div class="card-body">
        @if($errors->any())<x-adminlte-alert theme="danger" dismissable><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></x-adminlte-alert>@endif
        <div class="row">
            <div class="col-md-6">
                <div class="form-group"><label>Name *</label><input name="name" class="form-control" value="{{ old('name', $client->name) }}" required></div>
                <div class="form-group"><label>Client Type *</label>
                    <select name="client_type" class="form-control" required>
                        <option value="individual" {{ old('client_type', $client->client_type) === 'individual' ? 'selected' : '' }}>Individual</option>
                        <option value="business"   {{ old('client_type', $client->client_type) === 'business'   ? 'selected' : '' }}>Business</option>
                    </select>
                </div>
                <div class="form-group"><label>Email</label><input name="email" type="email" class="form-control" value="{{ old('email', $client->email) }}"></div>
                <div class="form-group"><label>Phone</label><input name="phone" class="form-control" value="{{ old('phone', $client->phone) }}"></div>
                <div class="form-group"><label>Alternate Phone</label><input name="alternate_phone" class="form-control" value="{{ old('alternate_phone', $client->alternate_phone) }}"></div>
                <div class="form-group"><label>GSTIN</label><input name="gstin" class="form-control" value="{{ old('gstin', $client->gstin) }}"></div>
                <div class="form-group"><label>PAN</label><input name="pan" class="form-control" value="{{ old('pan', $client->pan) }}"></div>
            </div>
            <div class="col-md-6">
                <h5>Billing Address</h5>
                <div class="form-group"><label>Address *</label><textarea name="billing_address" class="form-control" rows="2" required>{{ old('billing_address', $client->billing_address) }}</textarea></div>
                <div class="row">
                    <div class="col-6"><div class="form-group"><label>City *</label><input name="billing_city" class="form-control" value="{{ old('billing_city', $client->billing_city) }}" required></div></div>
                    <div class="col-6"><div class="form-group"><label>Pincode *</label><input name="billing_pincode" class="form-control" value="{{ old('billing_pincode', $client->billing_pincode) }}" required></div></div>
                </div>
                <div class="row">
                    <div class="col-8"><div class="form-group"><label>State *</label><input name="billing_state" class="form-control" value="{{ old('billing_state', $client->billing_state) }}" required></div></div>
                    <div class="col-4"><div class="form-group"><label>Code *</label><input name="billing_state_code" class="form-control" value="{{ old('billing_state_code', $client->billing_state_code) }}" required></div></div>
                </div>
                <hr><h5>Shipping Address</h5>
                <div class="form-group"><label>Address</label><textarea name="shipping_address" class="form-control" rows="2">{{ old('shipping_address', $client->shipping_address) }}</textarea></div>
                <div class="row">
                    <div class="col-6"><div class="form-group"><label>City</label><input name="shipping_city" class="form-control" value="{{ old('shipping_city', $client->shipping_city) }}"></div></div>
                    <div class="col-6"><div class="form-group"><label>Pincode</label><input name="shipping_pincode" class="form-control" value="{{ old('shipping_pincode', $client->shipping_pincode) }}"></div></div>
                </div>
                <div class="row">
                    <div class="col-8"><div class="form-group"><label>State</label><input name="shipping_state" class="form-control" value="{{ old('shipping_state', $client->shipping_state) }}"></div></div>
                    <div class="col-4"><div class="form-group"><label>Code</label><input name="shipping_state_code" class="form-control" value="{{ old('shipping_state_code', $client->shipping_state_code) }}"></div></div>
                </div>
            </div>
        </div>
        <div class="form-group"><label>Notes</label><textarea name="notes" class="form-control" rows="2">{{ old('notes', $client->notes) }}</textarea></div>
        <div class="custom-control custom-switch"><input type="checkbox" name="is_active" class="custom-control-input" id="is_active" value="1" {{ old('is_active', $client->is_active) ? 'checked' : '' }}><label class="custom-control-label" for="is_active">Active</label></div>
    </div>
    <div class="card-footer">
        <button type="submit" class="btn btn-primary">Update Client</button>
        <a href="{{ route('admin.clients.index') }}" class="btn btn-default ml-2">Cancel</a>
    </div>
    </form>
</div>
@endsection
