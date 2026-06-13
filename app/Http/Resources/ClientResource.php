<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ClientResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'name'           => $this->name,
            'email'          => $this->email,
            'phone'          => $this->phone,
            'alternate_phone'=> $this->alternate_phone,
            'client_type'    => $this->client_type,
            'gstin'          => $this->gstin,
            'pan'            => $this->pan,
            'billing_address' => [
                'address'    => $this->billing_address,
                'city'       => $this->billing_city,
                'state'      => $this->billing_state,
                'state_code' => $this->billing_state_code,
                'pincode'    => $this->billing_pincode,
            ],
            'shipping_address' => $this->shipping_address ? [
                'address'    => $this->shipping_address,
                'city'       => $this->shipping_city,
                'state'      => $this->shipping_state,
                'state_code' => $this->shipping_state_code,
                'pincode'    => $this->shipping_pincode,
            ] : null,
            'notes'          => $this->notes,
            'is_active'      => $this->is_active,
            'created_at'     => $this->created_at,
        ];
    }
}
