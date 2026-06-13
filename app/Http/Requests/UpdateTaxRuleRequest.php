<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaxRuleRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('taxRule')->id;

        return [
            'name'        => "sometimes|string|max:100|unique:tax_rules,name,{$id}",
            'type'        => 'sometimes|in:GST,Exempt',
            'cgst_rate'   => 'sometimes|numeric|min:0|max:50',
            'sgst_rate'   => 'sometimes|numeric|min:0|max:50',
            'igst_rate'   => 'sometimes|numeric|min:0|max:100',
            'description' => 'sometimes|nullable|string|max:500',
            'is_active'   => 'sometimes|boolean',
        ];
    }
}
