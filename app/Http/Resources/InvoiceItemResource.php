<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'item_id'          => $this->item_id,
            'description'      => $this->description,
            'hsn_code'         => $this->hsn_code,
            'quantity'         => (float) $this->quantity,
            'unit'             => $this->unit,
            'rate'             => (float) $this->rate,
            'discount_percent' => (float) $this->discount_percent,
            'discount_amount'  => (float) $this->discount_amount,
            'taxable_amount'   => (float) $this->taxable_amount,
            'cgst_rate'        => (float) $this->cgst_rate,
            'cgst_amount'      => (float) $this->cgst_amount,
            'sgst_rate'        => (float) $this->sgst_rate,
            'sgst_amount'      => (float) $this->sgst_amount,
            'igst_rate'        => (float) $this->igst_rate,
            'igst_amount'      => (float) $this->igst_amount,
            'total'            => (float) $this->total,
            'sort_order'       => $this->sort_order,
        ];
    }
}
