<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'description'   => $this->description,
            'hsn_code'      => $this->hsn_code,
            'unit'          => $this->unit,
            'selling_price' => (float) $this->selling_price,
            'category'      => $this->category,
            'tax_rule'      => new TaxRuleResource($this->whenLoaded('taxRule')),
            'tax_rule_id'   => $this->tax_rule_id,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at,
        ];
    }
}
