<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'invoice_number'   => $this->invoice_number,
            'invoice_date'     => $this->invoice_date?->format('Y-m-d'),
            'due_date'         => $this->due_date?->format('Y-m-d'),
            'supply_type'      => $this->supply_type,
            'place_of_supply'  => $this->place_of_supply,
            'financial_year'   => $this->financial_year,
            'status'           => $this->status,
            'payment_status'   => $this->payment_status,

            'company'          => new CompanyResource($this->whenLoaded('company')),
            'client'           => new ClientResource($this->whenLoaded('client')),

            'items'            => InvoiceItemResource::collection($this->whenLoaded('items')),

            'amounts' => [
                'subtotal'        => (float) $this->subtotal,
                'discount'        => (float) $this->discount_amount,
                'taxable_amount'  => (float) $this->taxable_amount,
                'cgst_total'      => (float) $this->cgst_total,
                'sgst_total'      => (float) $this->sgst_total,
                'igst_total'      => (float) $this->igst_total,
                'total_tax'       => (float) $this->total_tax,
                'round_off'       => (float) $this->round_off,
                'grand_total'     => (float) $this->grand_total,
                'amount_paid'     => (float) $this->amount_paid,
                'balance_due'     => (float) $this->balance_due,
            ],

            'notes'    => $this->notes,
            'terms'    => $this->terms,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
