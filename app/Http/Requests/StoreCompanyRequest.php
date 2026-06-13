<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompanyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'                  => 'required|string|max:255',
            'address'               => 'required|string',
            'city'                  => 'required|string|max:100',
            'state'                 => 'required|string|max:100',
            'state_code'            => 'required|string|size:2',
            'pincode'               => 'required|string|max:10',
            'gstin'                 => ['nullable', 'string', 'size:15', 'unique:companies,gstin', 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'pan'                   => ['nullable', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'phone'                 => 'required|string|max:20',
            'alternate_phone'       => 'nullable|string|max:20',
            'email'                 => 'required|email|max:255',
            'website'               => 'nullable|url|max:255',
            'bank_name'             => 'nullable|string|max:255',
            'bank_account_number'   => 'nullable|string|max:50',
            'bank_ifsc'             => ['nullable', 'string', 'max:11', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            'bank_branch'           => 'nullable|string|max:255',
            'invoice_prefix'        => 'nullable|string|max:20',
            'financial_year'        => 'nullable|string|max:10',
        ];
    }

    public function messages(): array
    {
        return [
            'gstin.regex'      => 'GSTIN format is invalid. Example: 24AABCU9603R1ZX',
            'pan.regex'        => 'PAN format is invalid. Example: AABCU9603R',
            'bank_ifsc.regex'  => 'IFSC code format is invalid. Example: SBIN0001234',
        ];
    }
}
