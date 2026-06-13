<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'company_id'       => 'required|exists:companies,id',
            'client_id'        => 'required|exists:clients,id',
            'invoice_date'     => 'required|date',
            'due_date'         => 'nullable|date|after_or_equal:invoice_date',
            'supply_type'      => 'required|in:intra,inter',
            'place_of_supply'  => 'required|string|size:2',
            'discount_amount'  => 'nullable|numeric|min:0',
            'notes'            => 'nullable|string',
            'terms'            => 'nullable|string',
            'status'           => 'nullable|in:draft,sent',

            'items'                         => 'required|array|min:1',
            'items.*.item_id'               => 'nullable|exists:items,id',
            'items.*.description'           => 'required|string|max:500',
            'items.*.hsn_code'              => 'nullable|string|max:20',
            'items.*.quantity'              => 'required|numeric|min:0.001',
            'items.*.unit'                  => 'required|in:Nos,Sqft,Rft,Set,Pair,Kg,Meter,Box,Rmt',
            'items.*.rate'                  => 'required|numeric|min:0',
            'items.*.discount_percent'      => 'nullable|numeric|min:0|max:100',
            'items.*.tax_rule_id'           => 'nullable|exists:tax_rules,id',
        ];
    }
}
