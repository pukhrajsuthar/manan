<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Item;

class DashboardController extends Controller
{
    public function index()
    {
        $fy   = config('app.financial_year', '2024-25');
        $base = Invoice::where('financial_year', $fy)->whereNot('status', 'cancelled');

        $stats = [
            'total_invoices'    => (clone $base)->count(),
            'total_revenue'     => (clone $base)->sum('grand_total'),
            'total_outstanding' => (clone $base)->sum('balance_due'),
            'overdue_count'     => (clone $base)->where('due_date', '<', now())->whereNot('status', 'paid')->count(),
            'total_clients'     => Client::count(),
            'total_companies'   => Company::count(),
            'total_items'       => Item::count(),
            'draft_count'       => (clone $base)->where('status', 'draft')->count(),
            'sent_count'        => (clone $base)->where('status', 'sent')->count(),
            'paid_count'        => (clone $base)->where('status', 'paid')->count(),
        ];

        $recent_invoices = Invoice::with(['client', 'company'])
            ->orderByDesc('invoice_date')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_invoices', 'fy'));
    }
}
