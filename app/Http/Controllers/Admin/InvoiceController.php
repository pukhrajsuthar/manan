<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Item;
use App\Models\PaymentLog;
use App\Models\TaxRule;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $invoices = Invoice::with(['client', 'company'])
            ->when($request->status,         fn ($q) => $q->where('status', $request->status))
            ->when($request->payment_status, fn ($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->search,         fn ($q) => $q->where('invoice_number', 'like', "%{$request->search}%"))
            ->orderByDesc('invoice_date')
            ->paginate(20)->withQueryString();

        return view('admin.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $companies = Company::where('is_active', true)->get();
        $clients   = Client::where('is_active', true)->orderBy('name')->get();
        $items     = Item::where('is_active', true)->with('taxRule')->orderBy('name')->get();
        $taxRules  = TaxRule::where('is_active', true)->get();
        return view('admin.invoices.create', compact('companies', 'clients', 'items', 'taxRules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_id'     => 'required|exists:companies,id',
            'client_id'      => 'required|exists:clients,id',
            'invoice_number' => 'required|string|max:30|unique:invoices,invoice_number',
            'invoice_date'   => 'required|date',
            'due_date'       => 'nullable|date',
            'supply_type'    => 'required|in:intra,inter',
            'financial_year' => 'required|string|max:10',
            'notes'          => 'nullable|string',
            'terms'          => 'nullable|string',
            'lines'          => 'required|array|min:1',
            'lines.*.description'   => 'required|string|max:255',
            'lines.*.quantity'      => 'required|numeric|min:0.001',
            'lines.*.rate'          => 'required|numeric|min:0',
            'lines.*.unit'          => 'required|string|max:20',
            'lines.*.hsn_code'      => 'nullable|string|max:20',
            'lines.*.item_id'       => 'nullable|exists:items,id',
            'lines.*.tax_rule_id'   => 'nullable|exists:tax_rules,id',
            'lines.*.discount_pct'  => 'nullable|numeric|min:0|max:100',
        ]);

        $company     = Company::findOrFail($request->company_id);
        $client      = Client::findOrFail($request->client_id);
        $supplyType  = $request->supply_type;

        // Build line-level calculations
        $lineData        = [];
        $subtotal        = 0;
        $invoiceDiscount = 0;
        $invoiceCgst     = 0;
        $invoiceSgst     = 0;
        $invoiceIgst     = 0;

        foreach ($request->lines as $i => $line) {
            $qty           = (float) $line['quantity'];
            $rate          = (float) $line['rate'];
            $discPct       = (float) ($line['discount_pct'] ?? 0);
            $lineSubtotal  = round($qty * $rate, 2);
            $discAmt       = round($lineSubtotal * $discPct / 100, 2);
            $taxable       = round($lineSubtotal - $discAmt, 2);

            $cgstRate = $sgstRate = $igstRate = 0.0;
            $cgstAmt  = $sgstAmt  = $igstAmt  = 0.0;

            if (!empty($line['tax_rule_id'])) {
                $rule = TaxRule::find($line['tax_rule_id']);
                if ($rule) {
                    if ($supplyType === 'intra') {
                        $cgstRate = (float) $rule->cgst_rate;
                        $sgstRate = (float) $rule->sgst_rate;
                        $cgstAmt  = round($taxable * $cgstRate / 100, 2);
                        $sgstAmt  = round($taxable * $sgstRate / 100, 2);
                    } else {
                        $igstRate = (float) $rule->igst_rate;
                        $igstAmt  = round($taxable * $igstRate / 100, 2);
                    }
                }
            }

            $lineTotal = round($taxable + $cgstAmt + $sgstAmt + $igstAmt, 2);

            $lineData[]       = compact(
                'qty', 'rate', 'discPct', 'discAmt', 'taxable',
                'cgstRate', 'sgstRate', 'igstRate',
                'cgstAmt', 'sgstAmt', 'igstAmt', 'lineTotal'
            ) + ['line' => $line, 'sort' => $i];

            $subtotal        += $lineSubtotal;
            $invoiceDiscount += $discAmt;
            $invoiceCgst     += $cgstAmt;
            $invoiceSgst     += $sgstAmt;
            $invoiceIgst     += $igstAmt;
        }

        $taxableAmount = round($subtotal - $invoiceDiscount, 2);
        $totalTax      = round($invoiceCgst + $invoiceSgst + $invoiceIgst, 2);
        $grandTotal    = round($taxableAmount + $totalTax, 2);

        DB::transaction(function () use (
            $request, $company, $client, $lineData,
            $subtotal, $invoiceDiscount, $taxableAmount,
            $invoiceCgst, $invoiceSgst, $invoiceIgst, $totalTax, $grandTotal
        ) {
            $invoice = Invoice::create([
                'invoice_number'  => $request->invoice_number,
                'invoice_date'    => $request->invoice_date,
                'due_date'        => $request->due_date,
                'company_id'      => $company->id,
                'client_id'       => $client->id,
                'supply_type'     => $request->supply_type,
                'place_of_supply' => $client->billing_state_code,
                'subtotal'        => $subtotal,
                'discount_amount' => $invoiceDiscount,
                'taxable_amount'  => $taxableAmount,
                'cgst_total'      => $invoiceCgst,
                'sgst_total'      => $invoiceSgst,
                'igst_total'      => $invoiceIgst,
                'total_tax'       => $totalTax,
                'grand_total'     => $grandTotal,
                'round_off'       => 0,
                'amount_paid'     => 0,
                'balance_due'     => $grandTotal,
                'payment_status'  => 'unpaid',
                'status'          => 'draft',
                'notes'           => $request->notes,
                'terms'           => $request->terms,
                'financial_year'  => $request->financial_year,
            ]);

            foreach ($lineData as $ld) {
                $l = $ld['line'];
                InvoiceItem::create([
                    'invoice_id'      => $invoice->id,
                    'item_id'         => $l['item_id'] ?? null,
                    'description'     => $l['description'],
                    'hsn_code'        => $l['hsn_code'] ?? null,
                    'quantity'        => $ld['qty'],
                    'unit'            => $l['unit'],
                    'rate'            => $ld['rate'],
                    'discount_percent'=> $ld['discPct'],
                    'discount_amount' => $ld['discAmt'],
                    'taxable_amount'  => $ld['taxable'],
                    'cgst_rate'       => $ld['cgstRate'],
                    'cgst_amount'     => $ld['cgstAmt'],
                    'sgst_rate'       => $ld['sgstRate'],
                    'sgst_amount'     => $ld['sgstAmt'],
                    'igst_rate'       => $ld['igstRate'],
                    'igst_amount'     => $ld['igstAmt'],
                    'total'           => $ld['lineTotal'],
                    'sort_order'      => $ld['sort'],
                ]);
            }

            $company->incrementInvoiceCounter();
        });

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['company', 'client', 'items.item.taxRule', 'paymentLogs']);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function destroy(Invoice $invoice)
    {
        abort_if($invoice->status === 'paid', 422, 'Paid invoices cannot be deleted.');
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted.');
    }

    public function markSent(Invoice $invoice)
    {
        abort_if($invoice->status === 'cancelled', 422, 'Cancelled invoices cannot be sent.');
        $invoice->update(['status' => 'sent']);
        return back()->with('success', 'Invoice marked as sent.');
    }

    public function cancel(Invoice $invoice)
    {
        abort_if($invoice->status === 'paid', 422, 'Paid invoices cannot be cancelled.');
        $invoice->update(['status' => 'cancelled']);
        return back()->with('success', 'Invoice cancelled.');
    }

    public function recordPayment(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,cheque,bank_transfer,upi',
            'payment_date'   => 'required|date',
            'reference'      => 'nullable|string|max:100',
            'note'           => 'nullable|string|max:500',
        ]);

        $balanceBefore = $invoice->balance_due;
        $paid          = $invoice->amount_paid + $request->amount;
        $balance       = $invoice->grand_total - $paid;
        $balanceAfter  = max(0, $balance);

        $invoice->update([
            'amount_paid'    => $paid,
            'balance_due'    => $balanceAfter,
            'payment_status' => $balance <= 0 ? 'paid' : 'partial',
            'status'         => $balance <= 0 ? 'paid' : $invoice->status,
        ]);

        PaymentLog::create([
            'invoice_id'     => $invoice->id,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'reference'      => $request->reference,
            'payment_date'   => $request->payment_date,
            'note'           => $request->note,
            'balance_before' => $balanceBefore,
            'balance_after'  => $balanceAfter,
        ]);

        return back()->with('success', 'Payment of ₹' . number_format($request->amount, 2) . ' recorded.');
    }

    public function printView(Invoice $invoice)
    {
        $invoice->load(['company', 'client', 'items.item.taxRule']);
        $isPdf = false;
        return view('admin.invoices.print', compact('invoice', 'isPdf'));
    }

    public function downloadPdf(Invoice $invoice)
    {
        $invoice->load(['company', 'client', 'items.item.taxRule']);
        $isPdf = true;
        $pdf = Pdf::loadView('admin.invoices.print', compact('invoice', 'isPdf'))
            ->setPaper('a4', 'portrait')
            ->setOption(['dpi' => 96, 'defaultFont' => 'dejavu sans', 'isHtml5ParserEnabled' => true]);
        $filename = $invoice->invoice_number . '.pdf';
        return $pdf->download($filename);
    }
}
