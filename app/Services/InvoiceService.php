<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\TaxRule;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    /**
     * Create a new invoice with line items and recalculate totals.
     */
    public function create(Company $company, array $data): Invoice
    {
        return DB::transaction(function () use ($company, $data) {
            $invoiceNumber = $company->generateInvoiceNumber();

            $invoice = Invoice::create([
                'invoice_number'  => $invoiceNumber,
                'invoice_date'    => $data['invoice_date'],
                'due_date'        => $data['due_date'] ?? null,
                'company_id'      => $company->id,
                'client_id'       => $data['client_id'],
                'supply_type'     => $data['supply_type'] ?? 'intra',
                'place_of_supply' => $data['place_of_supply'],
                'notes'           => $data['notes'] ?? null,
                'terms'           => $data['terms'] ?? null,
                'financial_year'  => $company->financial_year,
                'status'          => $data['status'] ?? 'draft',
            ]);

            $this->syncItems($invoice, $data['items']);
            $this->recalculateTotals($invoice, $data['discount_amount'] ?? 0);

            $company->incrementInvoiceCounter();

            return $invoice->fresh(['items', 'client', 'company']);
        });
    }

    /**
     * Update an existing draft invoice.
     */
    public function update(Invoice $invoice, array $data): Invoice
    {
        return DB::transaction(function () use ($invoice, $data) {
            $invoice->update([
                'invoice_date'    => $data['invoice_date'] ?? $invoice->invoice_date,
                'due_date'        => $data['due_date'] ?? $invoice->due_date,
                'client_id'       => $data['client_id'] ?? $invoice->client_id,
                'supply_type'     => $data['supply_type'] ?? $invoice->supply_type,
                'place_of_supply' => $data['place_of_supply'] ?? $invoice->place_of_supply,
                'notes'           => $data['notes'] ?? $invoice->notes,
                'terms'           => $data['terms'] ?? $invoice->terms,
                'status'          => $data['status'] ?? $invoice->status,
            ]);

            if (isset($data['items'])) {
                $this->syncItems($invoice, $data['items']);
            }

            $this->recalculateTotals($invoice, $data['discount_amount'] ?? $invoice->discount_amount);

            return $invoice->fresh(['items', 'client', 'company']);
        });
    }

    /**
     * Record a payment against an invoice.
     */
    public function recordPayment(Invoice $invoice, float $amount): Invoice
    {
        $newPaid = $invoice->amount_paid + $amount;

        if ($newPaid > $invoice->grand_total) {
            throw new \InvalidArgumentException('Payment amount exceeds invoice balance.');
        }

        $balance = $invoice->grand_total - $newPaid;

        $invoice->update([
            'amount_paid'    => $newPaid,
            'balance_due'    => $balance,
            'payment_status' => $balance <= 0 ? 'paid' : 'partial',
            'status'         => $balance <= 0 ? 'paid' : $invoice->status,
        ]);

        return $invoice->fresh();
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function syncItems(Invoice $invoice, array $itemsData): void
    {
        // Delete removed items
        $incoming = collect($itemsData)->pluck('id')->filter();
        $invoice->items()->whereNotIn('id', $incoming)->delete();

        foreach ($itemsData as $index => $itemData) {
            $this->upsertItem($invoice, $itemData, $index, $invoice->supply_type === 'inter');
        }
    }

    private function upsertItem(Invoice $invoice, array $data, int $sortOrder, bool $isInterState): void
    {
        /** @var TaxRule|null $taxRule */
        $taxRule = isset($data['tax_rule_id'])
            ? TaxRule::find($data['tax_rule_id'])
            : null;

        $qty      = (float) $data['quantity'];
        $rate     = (float) $data['rate'];
        $discPct  = (float) ($data['discount_percent'] ?? 0);

        $grossAmount   = round($qty * $rate, 2);
        $discountAmt   = round($grossAmount * $discPct / 100, 2);
        $taxableAmount = round($grossAmount - $discountAmt, 2);

        $cgstRate = 0.00;
        $sgstRate = 0.00;
        $igstRate = 0.00;

        if ($taxRule && $taxRule->type === 'GST') {
            if ($isInterState) {
                $igstRate = (float) $taxRule->igst_rate;
            } else {
                $cgstRate = (float) $taxRule->cgst_rate;
                $sgstRate = (float) $taxRule->sgst_rate;
            }
        }

        $cgstAmount = round($taxableAmount * $cgstRate / 100, 2);
        $sgstAmount = round($taxableAmount * $sgstRate / 100, 2);
        $igstAmount = round($taxableAmount * $igstRate / 100, 2);
        $total      = round($taxableAmount + $cgstAmount + $sgstAmount + $igstAmount, 2);

        $attributes = [
            'invoice_id'       => $invoice->id,
            'item_id'          => $data['item_id'] ?? null,
            'description'      => $data['description'],
            'hsn_code'         => $data['hsn_code'] ?? null,
            'quantity'         => $qty,
            'unit'             => $data['unit'] ?? 'Nos',
            'rate'             => $rate,
            'discount_percent' => $discPct,
            'discount_amount'  => $discountAmt,
            'taxable_amount'   => $taxableAmount,
            'cgst_rate'        => $cgstRate,
            'cgst_amount'      => $cgstAmount,
            'sgst_rate'        => $sgstRate,
            'sgst_amount'      => $sgstAmount,
            'igst_rate'        => $igstRate,
            'igst_amount'      => $igstAmount,
            'total'            => $total,
            'sort_order'       => $sortOrder,
        ];

        if (!empty($data['id'])) {
            InvoiceItem::where('id', $data['id'])->where('invoice_id', $invoice->id)->update($attributes);
        } else {
            InvoiceItem::create($attributes);
        }
    }

    private function recalculateTotals(Invoice $invoice, float $overallDiscount = 0): void
    {
        $invoice->load('items');

        $subtotal      = $invoice->items->sum('taxable_amount') + $invoice->items->sum('discount_amount');
        $taxableAmount = $invoice->items->sum('taxable_amount');
        $cgstTotal     = $invoice->items->sum('cgst_amount');
        $sgstTotal     = $invoice->items->sum('sgst_amount');
        $igstTotal     = $invoice->items->sum('igst_amount');
        $totalTax      = round($cgstTotal + $sgstTotal + $igstTotal, 2);
        $grandRaw      = round($taxableAmount + $totalTax, 2);
        $roundOff      = round(round($grandRaw) - $grandRaw, 2);
        $grandTotal    = round($grandRaw + $roundOff, 2);
        $balanceDue    = round($grandTotal - $invoice->amount_paid, 2);

        $invoice->update([
            'subtotal'        => round($subtotal, 2),
            'discount_amount' => round($overallDiscount, 2),
            'taxable_amount'  => round($taxableAmount, 2),
            'cgst_total'      => round($cgstTotal, 2),
            'sgst_total'      => round($sgstTotal, 2),
            'igst_total'      => round($igstTotal, 2),
            'total_tax'       => $totalTax,
            'grand_total'     => $grandTotal,
            'round_off'       => $roundOff,
            'balance_due'     => $balanceDue,
            'payment_status'  => $balanceDue <= 0 ? 'paid' : ($invoice->amount_paid > 0 ? 'partial' : 'unpaid'),
        ]);
    }
}
