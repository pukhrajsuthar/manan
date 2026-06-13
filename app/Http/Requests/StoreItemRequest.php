<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'hsn_code'      => 'nullable|string|max:20',
            'unit'          => 'required|in:Nos,Sqft,Rft,Set,Pair,Kg,Meter,Box,Rmt',
            'selling_price' => 'required|numeric|min:0',
            'tax_rule_id'   => 'required|exists:tax_rules,id',
            'category'      => 'nullable|string|max:100',
        ];
    }
}
