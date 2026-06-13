<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxRule extends Model
{
    protected $fillable = [
        'name', 'type', 'cgst_rate', 'sgst_rate', 'igst_rate', 'description', 'is_active',
    ];

    protected $casts = [
        'cgst_rate' => 'decimal:2',
        'sgst_rate' => 'decimal:2',
        'igst_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }

    public function getTotalGstRateAttribute(): float
    {
        return (float) ($this->cgst_rate + $this->sgst_rate);
    }
}
