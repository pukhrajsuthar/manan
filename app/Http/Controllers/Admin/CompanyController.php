<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->paginate(15);
        return view('admin.companies.index', compact('companies'));
    }

    public function create()
    {
        return view('admin.companies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'address'              => 'required|string',
            'city'                 => 'required|string|max:100',
            'state'                => 'required|string|max:100',
            'state_code'           => 'required|string|max:10',
            'pincode'              => 'required|string|max:10',
            'gstin'                => 'nullable|string|max:20',
            'pan'                  => 'nullable|string|max:15',
            'phone'                => 'nullable|string|max:20',
            'email'                => 'nullable|email|max:255',
            'website'              => 'nullable|url|max:255',
            'bank_name'            => 'nullable|string|max:100',
            'bank_account_number'  => 'nullable|string|max:30',
            'bank_ifsc'            => 'nullable|string|max:15',
            'bank_branch'          => 'nullable|string|max:100',
            'invoice_prefix'       => 'required|string|max:10',
            'financial_year'       => 'required|string|max:10',
            'currency'             => 'required|string|max:5',
            'is_active'            => 'boolean',
        ]);

        $data['is_active']       = $request->boolean('is_active', true);
        $data['invoice_counter'] = 1;

        Company::create($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company created.');
    }

    public function edit(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name'                 => 'required|string|max:255',
            'address'              => 'required|string',
            'city'                 => 'required|string|max:100',
            'state'                => 'required|string|max:100',
            'state_code'           => 'required|string|max:10',
            'pincode'              => 'required|string|max:10',
            'gstin'                => 'nullable|string|max:20',
            'pan'                  => 'nullable|string|max:15',
            'phone'                => 'nullable|string|max:20',
            'email'                => 'nullable|email|max:255',
            'website'              => 'nullable|url|max:255',
            'bank_name'            => 'nullable|string|max:100',
            'bank_account_number'  => 'nullable|string|max:30',
            'bank_ifsc'            => 'nullable|string|max:15',
            'bank_branch'          => 'nullable|string|max:100',
            'invoice_prefix'       => 'required|string|max:10',
            'financial_year'       => 'required|string|max:10',
            'currency'             => 'required|string|max:5',
            'is_active'            => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $company->update($data);
        return redirect()->route('admin.companies.index')->with('success', 'Company updated.');
    }

    public function destroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Company deleted.');
    }
}
