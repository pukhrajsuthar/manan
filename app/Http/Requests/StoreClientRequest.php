<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'email'                 => 'nullable|email|max:255',
            'phone'                 => 'required|string|max:20',
            'alternate_phone'       => 'nullable|string|max:20',

            // Billing address
            'billing_address'       => 'required|string',
            'billing_city'          => 'required|string|max:100',
            'billing_state'         => 'required|string|max:100',
            'billing_state_code'    => 'required|string|size:2',
            'billing_pincode'       => 'required|string|max:10',

            // Shipping address (optional)
            'shipping_address'      => 'nullable|string',
            'shipping_city'         => 'nullable|required_with:shipping_address|string|max:100',
            'shipping_state'        => 'nullable|required_with:shipping_address|string|max:100',
            'shipping_state_code'   => 'nullable|required_with:shipping_address|string|size:2',
            'shipping_pincode'      => 'nullable|required_with:shipping_address|string|max:10',

            'gstin'                 => ['nullable', 'string', 'size:15', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'pan'                   => ['nullable', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'client_type'           => 'nullable|in:individual,business',
            'notes'                 => 'nullable|string',
        ];
    }
}
