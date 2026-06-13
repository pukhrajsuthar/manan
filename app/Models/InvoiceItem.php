<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id', 'item_id', 'description', 'hsn_code',
        'quantity', 'unit', 'rate',
        'discount_percent', 'discount_amount', 'taxable_amount',
        'cgst_rate', 'cgst_amount', 'sgst_rate', 'sgst_amount',
        'igst_rate', 'igst_amount', 'total', 'sort_order',
    ];

    protected $casts = [
        'quantity'        => 'decimal:3',
        'rate'            => 'decimal:2',
        'discount_percent'=> 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable_amount'  => 'decimal:2',
        'cgst_rate'       => 'decimal:2',
        'cgst_amount'     => 'decimal:2',
        'sgst_rate'       => 'decimal:2',
        'sgst_amount'     => 'decimal:2',
        'igst_rate'       => 'decimal:2',
        'igst_amount'     => 'decimal:2',
        'total'           => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
