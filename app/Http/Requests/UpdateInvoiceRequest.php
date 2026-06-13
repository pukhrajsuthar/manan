<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'client_id'        => 'sometimes|exists:clients,id',
            'invoice_date'     => 'sometimes|date',
            'due_date'         => 'sometimes|nullable|date',
            'supply_type'      => 'sometimes|in:intra,inter',
            'place_of_supply'  => 'sometimes|string|size:2',
            'discount_amount'  => 'sometimes|numeric|min:0',
            'notes'            => 'sometimes|nullable|string',
            'terms'            => 'sometimes|nullable|string',
            'status'           => 'sometimes|in:draft,sent',

            'items'                     => 'sometimes|array|min:1',
            'items.*.id'                => 'nullable|exists:invoice_items,id',
            'items.*.item_id'           => 'nullable|exists:items,id',
            'items.*.description'       => 'required_with:items|string|max:500',
            'items.*.hsn_code'          => 'nullable|string|max:20',
            'items.*.quantity'          => 'required_with:items|numeric|min:0.001',
            'items.*.unit'              => 'required_with:items|in:Nos,Sqft,Rft,Set,Pair,Kg,Meter,Box,Rmt',
            'items.*.rate'              => 'required_with:items|numeric|min:0',
            'items.*.discount_percent'  => 'nullable|numeric|min:0|max:100',
            'items.*.tax_rule_id'       => 'nullable|exists:tax_rules,id',
        ];
    }
}
