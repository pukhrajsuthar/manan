<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'description', 'hsn_code', 'unit',
        'selling_price', 'tax_rule_id', 'category', 'is_active',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'is_active'     => 'boolean',
    ];

    public function taxRule(): BelongsTo
    {
        return $this->belongsTo(TaxRule::class);
    }

    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
