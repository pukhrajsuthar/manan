<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CompanyController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        $companies = Company::where('is_active', true)->get();
        return CompanyResource::collection($companies);
    }

    public function store(StoreCompanyRequest $request): CompanyResource
    {
        $company = Company::create($request->validated());
        return new CompanyResource($company);
    }

    public function show(Company $company): CompanyResource
    {
        return new CompanyResource($company);
    }

    public function update(UpdateCompanyRequest $request, Company $company): CompanyResource
    {
        $company->update($request->validated());
        return new CompanyResource($company->fresh());
    }

    public function destroy(Company $company): JsonResponse
    {
        $company->update(['is_active' => false]);
        return response()->json(['message' => 'Company deactivated.']);
    }
}
