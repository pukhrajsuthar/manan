<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $company = Company::where('name', 'Manan Furniture')->firstOrFail();

        $this->nirvanMehta($company);
        $this->bhaveshPatel($company);
        $this->shubhashTibrewal($company);
    }

    // -----------------------------------------------------------------------
    // Invoice 1: Nirvan Mehta — Roogta Estella | 29-Apr-2026 | Sent | Partial
    // Material total 33,34,400 + Labour 40% = 13,33,760 → Grand 46,68,160
    // -----------------------------------------------------------------------
    private function nirvanMehta(Company $company): void
    {
        $client = Client::where('name', 'Nirvan Mehta')->firstOrFail();

        $materialLines = [
            ['name' => 'Plywood 19mm',   'qty' => 3000.000, 'rate' => 200.00],
            ['name' => 'Plywood 12mm',   'qty' => 2000.000, 'rate' => 150.00],
            ['name' => 'Plywood 8mm',    'qty' => 1000.000, 'rate' => 100.00],
            ['name' => 'Plywood 6mm',    'qty' =>  600.000, 'rate' =>  80.00],
            ['name' => 'HDF 4mm',        'qty' => 4000.000, 'rate' =>  50.00],
            ['name' => 'HDF 6mm',        'qty' => 1500.000, 'rate' =>  70.00],
            ['name' => 'HDF 12mm',       'qty' =>  600.000, 'rate' => 120.00],
            ['name' => 'Inside Laminate','qty' =>  100.000, 'rate' => 1500.00],
            ['name' => 'Veneer 4mm',     'qty' => 1600.000, 'rate' => 250.00],
            ['name' => 'Cement Sheet',   'qty' => 1200.000, 'rate' => 125.00],
            ['name' => 'Wood / Timber',  'qty' =>   45.000, 'rate' => 6000.00],
            ['name' => 'Aluminium Pipe', 'qty' =>  300.000, 'rate' => 800.00],
            ['name' => 'Hardware',       'qty' =>    1.000, 'rate' => 200000.00],
            ['name' => 'Glass',          'qty' =>  800.000, 'rate' => 600.00],
            ['name' => 'Mix Metal',      'qty' =>    1.000, 'rate' => 19400.00],
        ];
        // Material sum = 33,34,400  |  Labour 40% = 13,33,760
        $materialSubtotal = 3334400.00;
        $labourAmount     = 1333760.00;
        $grandTotal       = $materialSubtotal + $labourAmount; // 46,68,160
        $amountPaid       = 1000000.00; // 10 lakh advance
        $balanceDue       = $grandTotal - $amountPaid;

        $invoice = Invoice::firstOrCreate(
            ['invoice_number' => 'MF-2627-001'],
            [
                'invoice_date'    => '2026-04-29',
                'due_date'        => '2026-07-29',
                'company_id'      => $company->id,
                'client_id'       => $client->id,
                'supply_type'     => 'intra',
                'place_of_supply' => '24',
                'subtotal'        => $grandTotal,
                'discount_amount' => 0.00,
                'taxable_amount'  => $grandTotal,
                'cgst_total'      => 0.00,
                'sgst_total'      => 0.00,
                'igst_total'      => 0.00,
                'total_tax'       => 0.00,
                'grand_total'     => $grandTotal,
                'round_off'       => 0.00,
                'amount_paid'     => $amountPaid,
                'balance_due'     => $balanceDue,
                'payment_status'  => 'partial',
                'status'          => 'sent',
                'notes'           => 'Site: Roogta Estella. Labour @ 40% of material cost.',
                'terms'           => 'Payment as per agreed schedule. Final amount may vary based on actual site measurements.',
                'financial_year'  => '2026-27',
            ]
        );

        if ($invoice->wasRecentlyCreated) {
            $this->addLines($invoice, $materialLines, 'Sqft');
            $labourItem = Item::where('name', 'Labour Charges')->first();
            InvoiceItem::create([
                'invoice_id'      => $invoice->id,
                'item_id'         => $labourItem?->id,
                'description'     => 'Labour Charges @ 40% on Material (₹33,34,400)',
                'hsn_code'        => null,
                'quantity'        => 1.000,
                'unit'            => 'Lot',
                'rate'            => $labourAmount,
                'discount_percent'=> 0.00,
                'discount_amount' => 0.00,
                'taxable_amount'  => $labourAmount,
                'cgst_rate'       => 0.00,
                'cgst_amount'     => 0.00,
                'sgst_rate'       => 0.00,
                'sgst_amount'     => 0.00,
                'igst_rate'       => 0.00,
                'igst_amount'     => 0.00,
                'total'           => $labourAmount,
                'sort_order'      => 16,
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // Invoice 2: Bhavesh Bhai Patel — Altera | 15-Jan-2026 | Sent | Unpaid
    // Material total 44,44,000 + Labour 36% = 15,99,840 → Grand 60,43,840
    // -----------------------------------------------------------------------
    private function bhaveshPatel(Company $company): void
    {
        $client = Client::where('name', 'Bhavesh Bhai Patel')->firstOrFail();

        $materialLines = [
            ['name' => 'Plywood 19mm',   'qty' => 4000.000, 'rate' => 200.00],
            ['name' => 'Plywood 12mm',   'qty' => 2500.000, 'rate' => 150.00],
            ['name' => 'Plywood 8mm',    'qty' => 1200.000, 'rate' => 100.00],
            ['name' => 'Plywood 6mm',    'qty' =>  800.000, 'rate' =>  80.00],
            ['name' => 'HDF 4mm',        'qty' => 5000.000, 'rate' =>  50.00],
            ['name' => 'HDF 6mm',        'qty' => 2000.000, 'rate' =>  70.00],
            ['name' => 'HDF 12mm',       'qty' =>  800.000, 'rate' => 120.00],
            ['name' => 'Inside Laminate','qty' =>  130.000, 'rate' => 1500.00],
            ['name' => 'Veneer 4mm',     'qty' => 2000.000, 'rate' => 250.00],
            ['name' => 'Cement Sheet',   'qty' => 1600.000, 'rate' => 125.00],
            ['name' => 'Wood / Timber',  'qty' =>   60.000, 'rate' => 6000.00],
            ['name' => 'Aluminium Pipe', 'qty' =>  400.000, 'rate' => 800.00],
            ['name' => 'Hardware',       'qty' =>    1.000, 'rate' => 300000.00],
            ['name' => 'Glass',          'qty' => 1000.000, 'rate' => 600.00],
            ['name' => 'Mix Metal',      'qty' =>    1.000, 'rate' => 124000.00],
        ];
        // Material sum = 44,44,000  |  Labour 36% = 15,99,840
        $materialSubtotal = 4444000.00;
        $labourAmount     = 1599840.00;
        $grandTotal       = $materialSubtotal + $labourAmount; // 60,43,840

        $invoice = Invoice::firstOrCreate(
            ['invoice_number' => 'MF-2526-001'],
            [
                'invoice_date'    => '2026-01-15',
                'due_date'        => '2026-04-15',
                'company_id'      => $company->id,
                'client_id'       => $client->id,
                'supply_type'     => 'intra',
                'place_of_supply' => '24',
                'subtotal'        => $grandTotal,
                'discount_amount' => 0.00,
                'taxable_amount'  => $grandTotal,
                'cgst_total'      => 0.00,
                'sgst_total'      => 0.00,
                'igst_total'      => 0.00,
                'total_tax'       => 0.00,
                'grand_total'     => $grandTotal,
                'round_off'       => 0.00,
                'amount_paid'     => 0.00,
                'balance_due'     => $grandTotal,
                'payment_status'  => 'unpaid',
                'status'          => 'sent',
                'notes'           => 'Site: B-1001, Altera. Labour @ 36% of material cost.',
                'terms'           => 'Payment as per agreed schedule. Final amount may vary based on actual site measurements.',
                'financial_year'  => '2025-26',
            ]
        );

        if ($invoice->wasRecentlyCreated) {
            $this->addLines($invoice, $materialLines, 'Sqft');
            $labourItem = Item::where('name', 'Labour Charges')->first();
            InvoiceItem::create([
                'invoice_id'      => $invoice->id,
                'item_id'         => $labourItem?->id,
                'description'     => 'Labour Charges @ 36% on Material (₹44,44,000)',
                'hsn_code'        => null,
                'quantity'        => 1.000,
                'unit'            => 'Lot',
                'rate'            => $labourAmount,
                'discount_percent'=> 0.00,
                'discount_amount' => 0.00,
                'taxable_amount'  => $labourAmount,
                'cgst_rate'       => 0.00,
                'cgst_amount'     => 0.00,
                'sgst_rate'       => 0.00,
                'sgst_amount'     => 0.00,
                'igst_rate'       => 0.00,
                'igst_amount'     => 0.00,
                'total'           => $labourAmount,
                'sort_order'      => 16,
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // Invoice 3: Shubhash Ji Tibrewal — Raskans Golf Link | Draft estimate
    // -----------------------------------------------------------------------
    private function shubhashTibrewal(Company $company): void
    {
        $client = Client::where('name', 'Shubhash Ji Tibrewal')->firstOrFail();

        $materialLines = [
            ['name' => 'Plywood 19mm',   'qty' => 2500.000, 'rate' => 200.00],
            ['name' => 'Plywood 12mm',   'qty' => 1800.000, 'rate' => 150.00],
            ['name' => 'Plywood 8mm',    'qty' =>  900.000, 'rate' => 100.00],
            ['name' => 'HDF 4mm',        'qty' => 3500.000, 'rate' =>  50.00],
            ['name' => 'HDF 6mm',        'qty' => 1200.000, 'rate' =>  70.00],
            ['name' => 'Inside Laminate','qty' =>   85.000, 'rate' => 1500.00],
            ['name' => 'Veneer 4mm',     'qty' => 1400.000, 'rate' => 250.00],
            ['name' => 'Cement Sheet',   'qty' =>  900.000, 'rate' => 125.00],
            ['name' => 'Wood / Timber',  'qty' =>   35.000, 'rate' => 6000.00],
            ['name' => 'Aluminium Pipe', 'qty' =>  220.000, 'rate' => 800.00],
            ['name' => 'Hardware',       'qty' =>    1.000, 'rate' => 175000.00],
            ['name' => 'Glass',          'qty' =>  600.000, 'rate' => 600.00],
            ['name' => 'Mix Metal',      'qty' =>    1.000, 'rate' => 51500.00],
        ];
        // Material sum: let PHP compute and use it
        $materialSubtotal = 0;
        foreach ($materialLines as $line) {
            $materialSubtotal += $line['qty'] * $line['rate'];
        }
        $labourPercent    = 38;
        $labourAmount     = round($materialSubtotal * $labourPercent / 100, 2);
        $grandTotal       = $materialSubtotal + $labourAmount;

        $invoice = Invoice::firstOrCreate(
            ['invoice_number' => 'MF-2526-002'],
            [
                'invoice_date'    => '2025-11-10',
                'due_date'        => null,
                'company_id'      => $company->id,
                'client_id'       => $client->id,
                'supply_type'     => 'intra',
                'place_of_supply' => '24',
                'subtotal'        => $grandTotal,
                'discount_amount' => 0.00,
                'taxable_amount'  => $grandTotal,
                'cgst_total'      => 0.00,
                'sgst_total'      => 0.00,
                'igst_total'      => 0.00,
                'total_tax'       => 0.00,
                'grand_total'     => $grandTotal,
                'round_off'       => 0.00,
                'amount_paid'     => 0.00,
                'balance_due'     => $grandTotal,
                'payment_status'  => 'unpaid',
                'status'          => 'draft',
                'notes'           => 'Site: Raskans Golf Link. Labour @ 38% of material cost. Draft estimate.',
                'terms'           => 'This is a preliminary estimate. Final amount subject to site inspection.',
                'financial_year'  => '2025-26',
            ]
        );

        if ($invoice->wasRecentlyCreated) {
            $this->addLines($invoice, $materialLines, 'Sqft');
            $labourItem = Item::where('name', 'Labour Charges')->first();
            InvoiceItem::create([
                'invoice_id'      => $invoice->id,
                'item_id'         => $labourItem?->id,
                'description'     => "Labour Charges @ {$labourPercent}% on Material (₹" . number_format($materialSubtotal, 0, '.', ',') . ')',
                'hsn_code'        => null,
                'quantity'        => 1.000,
                'unit'            => 'Lot',
                'rate'            => $labourAmount,
                'discount_percent'=> 0.00,
                'discount_amount' => 0.00,
                'taxable_amount'  => $labourAmount,
                'cgst_rate'       => 0.00,
                'cgst_amount'     => 0.00,
                'sgst_rate'       => 0.00,
                'sgst_amount'     => 0.00,
                'igst_rate'       => 0.00,
                'igst_amount'     => 0.00,
                'total'           => $labourAmount,
                'sort_order'      => 14,
            ]);
        }
    }

    // -----------------------------------------------------------------------
    // Helper: insert material line items for an invoice
    // -----------------------------------------------------------------------
    private function addLines(Invoice $invoice, array $lines, string $defaultUnit): void
    {
        $items = Item::whereIn('name', array_column($lines, 'name'))->get()->keyBy('name');

        foreach ($lines as $i => $line) {
            $item         = $items->get($line['name']);
            $taxable      = round($line['qty'] * $line['rate'], 2);

            InvoiceItem::create([
                'invoice_id'      => $invoice->id,
                'item_id'         => $item?->id,
                'description'     => $line['name'],
                'hsn_code'        => $item?->hsn_code,
                'quantity'        => $line['qty'],
                'unit'            => $item?->unit ?? $defaultUnit,
                'rate'            => $line['rate'],
                'discount_percent'=> 0.00,
                'discount_amount' => 0.00,
                'taxable_amount'  => $taxable,
                'cgst_rate'       => 0.00,
                'cgst_amount'     => 0.00,
                'sgst_rate'       => 0.00,
                'sgst_amount'     => 0.00,
                'igst_rate'       => 0.00,
                'igst_amount'     => 0.00,
                'total'           => $taxable,
                'sort_order'      => $i + 1,
            ]);
        }
    }
}
