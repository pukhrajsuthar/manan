<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxRule;
use Illuminate\Http\Request;

class TaxRuleController extends Controller
{
    public function index()
    {
        $taxRules = TaxRule::latest()->paginate(15);
        return view('admin.tax-rules.index', compact('taxRules'));
    }

    public function create()
    {
        return view('admin.tax-rules.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:gst,igst,exempt',
            'cgst_rate'   => 'required|numeric|min:0|max:100',
            'sgst_rate'   => 'required|numeric|min:0|max:100',
            'igst_rate'   => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        TaxRule::create($data);
        return redirect()->route('admin.tax-rules.index')->with('success', 'Tax rule created.');
    }

    public function edit(TaxRule $taxRule)
    {
        return view('admin.tax-rules.edit', compact('taxRule'));
    }

    public function update(Request $request, TaxRule $taxRule)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:100',
            'type'        => 'required|in:gst,igst,exempt',
            'cgst_rate'   => 'required|numeric|min:0|max:100',
            'sgst_rate'   => 'required|numeric|min:0|max:100',
            'igst_rate'   => 'required|numeric|min:0|max:100',
            'description' => 'nullable|string',
            'is_active'   => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $taxRule->update($data);
        return redirect()->route('admin.tax-rules.index')->with('success', 'Tax rule updated.');
    }

    public function destroy(TaxRule $taxRule)
    {
        $taxRule->delete();
        return redirect()->route('admin.tax-rules.index')->with('success', 'Tax rule deleted.');
    }
}
