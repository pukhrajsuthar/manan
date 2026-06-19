<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    protected $fillable = [
        'name', 'address', 'city', 'state', 'state_code', 'pincode',
        'gstin', 'pan', 'phone', 'alternate_phone', 'email', 'website',
        'bank_name', 'bank_account_number', 'bank_ifsc', 'bank_branch',
        'logo_path', 'invoice_prefix', 'invoice_counter', 'financial_year',
        'currency', 'is_active', 'show_discount', 'show_tax',
    ];

    protected $casts = [
        'is_active'      => 'boolean',
        'show_discount'  => 'boolean',
        'show_tax'       => 'boolean',
        'invoice_counter' => 'integer',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function generateInvoiceNumber(): string
    {
        $number  = str_pad($this->invoice_counter, 4, '0', STR_PAD_LEFT);
        $prefix  = strtoupper($this->invoice_prefix);
        $year    = $this->financial_year;

        return "{$prefix}/{$year}/{$number}";
    }

    public function incrementInvoiceCounter(): void
    {
        $this->increment('invoice_counter');
    }
}
