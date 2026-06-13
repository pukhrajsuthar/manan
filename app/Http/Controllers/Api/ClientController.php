<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ClientController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $clients = Client::query()
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('phone', 'like', "%{$request->search}%")
                ->orWhere('gstin', 'like', "%{$request->search}%"))
            ->when($request->type, fn ($q) => $q->where('client_type', $request->type))
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate($request->per_page ?? 20);

        return ClientResource::collection($clients);
    }

    public function store(StoreClientRequest $request): ClientResource
    {
        $client = Client::create($request->validated());
        return (new ClientResource($client))->response()->setStatusCode(201)->getData(true)
            ? new ClientResource($client)
            : new ClientResource($client);
    }

    public function show(Client $client): ClientResource
    {
        return new ClientResource($client);
    }

    public function update(UpdateClientRequest $request, Client $client): ClientResource
    {
        $client->update($request->validated());
        return new ClientResource($client->fresh());
    }

    public function destroy(Client $client): JsonResponse
    {
        $client->update(['is_active' => false]);
        $client->delete();
        return response()->json(['message' => 'Client removed.']);
    }
}
