<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecordPaymentRequest;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Models\Company;
use App\Models\Invoice;
use App\Services\InvoiceService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class InvoiceController extends Controller
{
    public function __construct(private readonly InvoiceService $invoiceService) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $invoices = Invoice::with(['client', 'company'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->payment_status, fn ($q) => $q->where('payment_status', $request->payment_status))
            ->when($request->client_id, fn ($q) => $q->where('client_id', $request->client_id))
            ->when($request->financial_year, fn ($q) => $q->where('financial_year', $request->financial_year))
            ->when($request->from_date, fn ($q) => $q->whereDate('invoice_date', '>=', $request->from_date))
            ->when($request->to_date, fn ($q) => $q->whereDate('invoice_date', '<=', $request->to_date))
            ->when($request->search, fn ($q) => $q->where('invoice_number', 'like', "%{$request->search}%"))
            ->orderByDesc('invoice_date')
            ->orderByDesc('id')
            ->paginate($request->per_page ?? 20);

        return InvoiceResource::collection($invoices);
    }

    public function store(StoreInvoiceRequest $request): InvoiceResource
    {
        $company = Company::findOrFail($request->company_id);
        $invoice = $this->invoiceService->create($company, $request->validated());
        return new InvoiceResource($invoice);
    }

    public function show(Invoice $invoice): InvoiceResource
    {
        $invoice->load(['company', 'client', 'items.item.taxRule']);
        return new InvoiceResource($invoice);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): InvoiceResource
    {
        abort_if(!$invoice->isEditable(), 422, 'Only draft invoices can be edited.');
        $invoice = $this->invoiceService->update($invoice, $request->validated());
        return new InvoiceResource($invoice);
    }

    public function destroy(Invoice $invoice): JsonResponse
    {
        abort_if($invoice->status === 'paid', 422, 'Paid invoices cannot be deleted.');
        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted.']);
    }

    public function recordPayment(RecordPaymentRequest $request, Invoice $invoice): InvoiceResource
    {
        $invoice = $this->invoiceService->recordPayment($invoice, $request->amount);
        return new InvoiceResource($invoice->fresh(['company', 'client', 'items']));
    }

    public function markSent(Invoice $invoice): InvoiceResource
    {
        abort_if($invoice->status === 'cancelled', 422, 'Cancelled invoices cannot be sent.');
        $invoice->update(['status' => 'sent']);
        return new InvoiceResource($invoice->fresh());
    }

    public function cancel(Invoice $invoice): InvoiceResource
    {
        abort_if($invoice->status === 'paid', 422, 'Paid invoices cannot be cancelled.');
        $invoice->update(['status' => 'cancelled']);
        return new InvoiceResource($invoice->fresh());
    }

    public function dashboard(Request $request): JsonResponse
    {
        $fy   = $request->financial_year ?? config('app.financial_year', '2024-25');
        $base = Invoice::where('financial_year', $fy)->whereNot('status', 'cancelled');

        $stats = [
            'total_invoices'   => (clone $base)->count(),
            'total_revenue'    => (clone $base)->sum('grand_total'),
            'total_paid'       => (clone $base)->sum('amount_paid'),
            'total_outstanding'=> (clone $base)->sum('balance_due'),
            'draft_count'      => (clone $base)->where('status', 'draft')->count(),
            'sent_count'       => (clone $base)->where('status', 'sent')->count(),
            'paid_count'       => (clone $base)->where('status', 'paid')->count(),
            'overdue_count'    => (clone $base)->where('due_date', '<', now())->whereNot('status', 'paid')->count(),
            'total_cgst'       => (clone $base)->sum('cgst_total'),
            'total_sgst'       => (clone $base)->sum('sgst_total'),
            'total_igst'       => (clone $base)->sum('igst_total'),
            'financial_year'   => $fy,
        ];

        return response()->json(['data' => $stats]);
    }
}
