<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaxRuleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'type'          => $this->type,
            'cgst_rate'     => (float) $this->cgst_rate,
            'sgst_rate'     => (float) $this->sgst_rate,
            'igst_rate'     => (float) $this->igst_rate,
            'total_gst'     => (float) ($this->cgst_rate + $this->sgst_rate),
            'description'   => $this->description,
            'is_active'     => $this->is_active,
            'created_at'    => $this->created_at,
        ];
    }
}
