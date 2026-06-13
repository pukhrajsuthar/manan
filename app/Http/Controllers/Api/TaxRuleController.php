<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaxRuleRequest;
use App\Http\Requests\UpdateTaxRuleRequest;
use App\Http\Resources\TaxRuleResource;
use App\Models\TaxRule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TaxRuleController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $rules = TaxRule::where('is_active', true)->orderBy('igst_rate')->get();
        return TaxRuleResource::collection($rules);
    }

    public function store(StoreTaxRuleRequest $request): TaxRuleResource
    {
        $rule = TaxRule::create($request->validated());
        return new TaxRuleResource($rule);
    }

    public function show(TaxRule $taxRule): TaxRuleResource
    {
        return new TaxRuleResource($taxRule);
    }

    public function update(UpdateTaxRuleRequest $request, TaxRule $taxRule): TaxRuleResource
    {
        $taxRule->update($request->validated());
        return new TaxRuleResource($taxRule->fresh());
    }

    public function destroy(TaxRule $taxRule): JsonResponse
    {
        if ($taxRule->items()->exists()) {
            return response()->json(['message' => 'Cannot delete tax rule — it is used by items.'], 422);
        }

        $taxRule->update(['is_active' => false]);
        return response()->json(['message' => 'Tax rule deactivated.']);
    }
}
