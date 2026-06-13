<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('company')->id;

        return [
            'name'                  => 'sometimes|string|max:255',
            'address'               => 'sometimes|string',
            'city'                  => 'sometimes|string|max:100',
            'state'                 => 'sometimes|string|max:100',
            'state_code'            => 'sometimes|string|size:2',
            'pincode'               => 'sometimes|string|max:10',
            'gstin'                 => ['sometimes', 'nullable', 'string', 'size:15', "unique:companies,gstin,{$id}", 'regex:/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/'],
            'pan'                   => ['sometimes', 'nullable', 'string', 'size:10', 'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/'],
            'phone'                 => 'sometimes|string|max:20',
            'alternate_phone'       => 'sometimes|nullable|string|max:20',
            'email'                 => 'sometimes|email|max:255',
            'website'               => 'sometimes|nullable|url|max:255',
            'bank_name'             => 'sometimes|nullable|string|max:255',
            'bank_account_number'   => 'sometimes|nullable|string|max:50',
            'bank_ifsc'             => ['sometimes', 'nullable', 'string', 'max:11', 'regex:/^[A-Z]{4}0[A-Z0-9]{6}$/'],
            'bank_branch'           => 'sometimes|nullable|string|max:255',
            'invoice_prefix'        => 'sometimes|string|max:20',
            'financial_year'        => 'sometimes|string|max:10',
        ];
    }
}
