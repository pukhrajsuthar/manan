<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'address'              => $this->address,
            'city'                 => $this->city,
            'state'                => $this->state,
            'state_code'           => $this->state_code,
            'pincode'              => $this->pincode,
            'gstin'                => $this->gstin,
            'pan'                  => $this->pan,
            'phone'                => $this->phone,
            'alternate_phone'      => $this->alternate_phone,
            'email'                => $this->email,
            'website'              => $this->website,
            'bank' => [
                'name'           => $this->bank_name,
                'account_number' => $this->bank_account_number,
                'ifsc'           => $this->bank_ifsc,
                'branch'         => $this->bank_branch,
            ],
            'invoice_prefix'       => $this->invoice_prefix,
            'invoice_counter'      => $this->invoice_counter,
            'financial_year'       => $this->financial_year,
            'currency'             => $this->currency,
            'is_active'            => $this->is_active,
            'created_at'           => $this->created_at,
        ];
    }
}
