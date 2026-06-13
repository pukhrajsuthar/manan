<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'          => 'sometimes|string|max:255',
            'description'   => 'sometimes|nullable|string',
            'hsn_code'      => 'sometimes|nullable|string|max:20',
            'unit'          => 'sometimes|in:Nos,Sqft,Rft,Set,Pair,Kg,Meter,Box,Rmt',
            'selling_price' => 'sometimes|numeric|min:0',
            'tax_rule_id'   => 'sometimes|exists:tax_rules,id',
            'category'      => 'sometimes|nullable|string|max:100',
            'is_active'     => 'sometimes|boolean',
        ];
    }
}
