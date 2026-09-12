<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Item;
use App\Models\TaxRule;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::with(['taxRule', 'category'])->latest()->paginate(15);
        return view('admin.items.index', compact('items'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $taxRules = TaxRule::where('is_active', true)->get();
        return view('admin.items.create', compact('categories', 'taxRules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'hsn_code'      => 'nullable|string|max:20',
            'unit'          => 'required|string|max:20',
            'selling_price' => 'required|numeric|min:0',
            'tax_rule_id'   => 'required|exists:tax_rules,id',
            'category_id'   => 'nullable|exists:categories,id',
            'is_active'     => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Item::create($data);
        return redirect()->route('admin.items.index')->with('success', 'Item created.');
    }

    public function edit(Item $item)
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $taxRules = TaxRule::where('is_active', true)->get();
        return view('admin.items.edit', compact('item', 'categories', 'taxRules'));
    }

    public function update(Request $request, Item $item)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'hsn_code'      => 'nullable|string|max:20',
            'unit'          => 'required|string|max:20',
            'selling_price' => 'required|numeric|min:0',
            'tax_rule_id'   => 'required|exists:tax_rules,id',
            'category_id'   => 'nullable|exists:categories,id',
            'is_active'     => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $item->update($data);
        return redirect()->route('admin.items.index')->with('success', 'Item updated.');
    }

    public function destroy(Item $item)
    {
        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Item deleted.');
    }
}
