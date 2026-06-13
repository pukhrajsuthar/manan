<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'invoice_number', 'invoice_date', 'due_date',
        'company_id', 'client_id',
        'supply_type', 'place_of_supply',
        'subtotal', 'discount_amount', 'taxable_amount',
        'cgst_total', 'sgst_total', 'igst_total', 'total_tax',
        'grand_total', 'round_off', 'amount_paid', 'balance_due',
        'payment_status', 'status',
        'notes', 'terms', 'financial_year',
    ];

    protected $casts = [
        'invoice_date'    => 'date',
        'due_date'        => 'date',
        'subtotal'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'taxable_amount'  => 'decimal:2',
        'cgst_total'      => 'decimal:2',
        'sgst_total'      => 'decimal:2',
        'igst_total'      => 'decimal:2',
        'total_tax'       => 'decimal:2',
        'grand_total'     => 'decimal:2',
        'round_off'       => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'balance_due'     => 'decimal:2',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class)->orderBy('sort_order');
    }

    public function paymentLogs(): HasMany
    {
        return $this->hasMany(PaymentLog::class)->orderByDesc('payment_date')->orderByDesc('id');
    }

    public function isInterState(): bool
    {
        return $this->supply_type === 'inter';
    }

    public function isEditable(): bool
    {
        return in_array($this->status, ['draft']);
    }
}
