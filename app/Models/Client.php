<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'alternate_phone',
        'billing_address', 'billing_city', 'billing_state', 'billing_state_code', 'billing_pincode',
        'shipping_address', 'shipping_city', 'shipping_state', 'shipping_state_code', 'shipping_pincode',
        'gstin', 'pan', 'client_type', 'notes', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    // Returns shipping address if set, otherwise falls back to billing address
    public function getEffectiveShippingAddressAttribute(): array
    {
        if ($this->shipping_address) {
            return [
                'address'    => $this->shipping_address,
                'city'       => $this->shipping_city,
                'state'      => $this->shipping_state,
                'state_code' => $this->shipping_state_code,
                'pincode'    => $this->shipping_pincode,
            ];
        }

        return [
            'address'    => $this->billing_address,
            'city'       => $this->billing_city,
            'state'      => $this->billing_state,
            'state_code' => $this->billing_state_code,
            'pincode'    => $this->billing_pincode,
        ];
    }
}
