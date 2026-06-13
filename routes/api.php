<?php

use App\Http\Controllers\Api\ClientController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\ItemController;
use App\Http\Controllers\Api\TaxRuleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Manan Furniture — Invoice Management API
|--------------------------------------------------------------------------
| All routes are prefixed with /api (via bootstrap/app.php or RouteServiceProvider)
*/

// Health check
Route::get('/health', fn () => response()->json(['status' => 'ok', 'app' => 'Manan Furniture Invoice API']));

// Company
Route::apiResource('companies', CompanyController::class);

// Clients
Route::apiResource('clients', ClientController::class);

// Tax Rules
Route::apiResource('tax-rules', TaxRuleController::class);

// Items / Products
Route::get('items/categories', [ItemController::class, 'categories']);
Route::apiResource('items', ItemController::class);

// Invoices
Route::prefix('invoices')->group(function () {
    Route::get('dashboard', [InvoiceController::class, 'dashboard']);
    Route::post('{invoice}/payment',   [InvoiceController::class, 'recordPayment']);
    Route::patch('{invoice}/send',     [InvoiceController::class, 'markSent']);
    Route::patch('{invoice}/cancel',   [InvoiceController::class, 'cancel']);
});
Route::apiResource('invoices', InvoiceController::class);
