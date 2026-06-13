<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => 'sometimes|string|max:255',
            'email'                 => 'sometimes|nullable|email|max:255',
            'phone'                 => 'sometimes|string|max:20',
            'alternate_phone'       => 'sometimes|nullable|string|max:20',
            'billing_address'       => 'sometimes|string',
            'billing_city'          => 'sometimes|string|max:100',
            'billing_state'         => 'sometimes|string|max:100',
            'billing_state_code'    => 'sometimes|string|size:2',
            'billing_pincode'       => 'sometimes|string|max:10',
            'shipping_address'      => 'sometimes|nullable|string',
            'shipping_city'         => 'sometimes|nullable|string|max:100',
            'shipping_state'        => 'sometimes|nullable|string|max:100',
            'shipping_state_code'   => 'sometimes|nullable|string|size:2',
            'shipping_pincode'      => 'sometimes|nullable|string|max:10',
            'gstin'                 => ['sometimes', 'nullable', 'string', 'size:15', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'pan'                   => ['sometimes', 'nullable', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'client_type'           => 'sometimes|in:individual,business',
            'notes'                 => 'sometimes|nullable|string',
        ];
    }
}
