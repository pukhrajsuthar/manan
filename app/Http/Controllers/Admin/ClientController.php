<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(15);
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'nullable|email|max:255',
            'phone'               => 'nullable|string|max:20',
            'alternate_phone'     => 'nullable|string|max:20',
            'billing_address'     => 'required|string',
            'billing_city'        => 'required|string|max:100',
            'billing_state'       => 'required|string|max:100',
            'billing_state_code'  => 'required|string|max:10',
            'billing_pincode'     => 'required|string|max:10',
            'shipping_address'    => 'nullable|string',
            'shipping_city'       => 'nullable|string|max:100',
            'shipping_state'      => 'nullable|string|max:100',
            'shipping_state_code' => 'nullable|string|max:10',
            'shipping_pincode'    => 'nullable|string|max:10',
            'gstin'               => 'nullable|string|max:20',
            'pan'                 => 'nullable|string|max:15',
            'client_type'         => 'required|in:individual,business',
            'notes'               => 'nullable|string',
            'is_active'           => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        Client::create($data);
        return redirect()->route('admin.clients.index')->with('success', 'Client created.');
    }

    public function edit(Client $client)
    {
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $data = $request->validate([
            'name'                => 'required|string|max:255',
            'email'               => 'nullable|email|max:255',
            'phone'               => 'nullable|string|max:20',
            'alternate_phone'     => 'nullable|string|max:20',
            'billing_address'     => 'required|string',
            'billing_city'        => 'required|string|max:100',
            'billing_state'       => 'required|string|max:100',
            'billing_state_code'  => 'required|string|max:10',
            'billing_pincode'     => 'required|string|max:10',
            'shipping_address'    => 'nullable|string',
            'shipping_city'       => 'nullable|string|max:100',
            'shipping_state'      => 'nullable|string|max:100',
            'shipping_state_code' => 'nullable|string|max:10',
            'shipping_pincode'    => 'nullable|string|max:10',
            'gstin'               => 'nullable|string|max:20',
            'pan'                 => 'nullable|string|max:15',
            'client_type'         => 'required|in:individual,business',
            'notes'               => 'nullable|string',
            'is_active'           => 'boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $client->update($data);
        return redirect()->route('admin.clients.index')->with('success', 'Client updated.');
    }

    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('admin.clients.index')->with('success', 'Client deleted.');
    }
}
