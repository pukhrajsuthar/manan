<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ItemController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $items = Item::with('taxRule')
            ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%")
                ->orWhere('hsn_code', 'like', "%{$request->search}%"))
            ->when($request->category, fn ($q) => $q->where('category', $request->category))
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate($request->per_page ?? 20);

        return ItemResource::collection($items);
    }

    public function store(StoreItemRequest $request): ItemResource
    {
        $item = Item::create($request->validated());
        return new ItemResource($item->load('taxRule'));
    }

    public function show(Item $item): ItemResource
    {
        return new ItemResource($item->load('taxRule'));
    }

    public function update(UpdateItemRequest $request, Item $item): ItemResource
    {
        $item->update($request->validated());
        return new ItemResource($item->fresh('taxRule'));
    }

    public function destroy(Item $item): JsonResponse
    {
        $item->update(['is_active' => false]);
        $item->delete();
        return response()->json(['message' => 'Item removed.']);
    }

    public function categories(): JsonResponse
    {
        $cats = Item::select('category')->distinct()->whereNotNull('category')->pluck('category');
        return response()->json(['data' => $cats]);
    }
}
