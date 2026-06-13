<?php

use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\TaxRuleController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('admin.dashboard'));

// Auth
Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin (requires auth)
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('companies', CompanyController::class)->except(['show']);
    Route::resource('clients',   ClientController::class)->except(['show']);
    Route::resource('tax-rules', TaxRuleController::class)->except(['show']);
    Route::resource('items',     ItemController::class)->except(['show']);

    Route::resource('invoices', InvoiceController::class)->only(['index', 'create', 'store', 'show', 'destroy']);
    Route::patch('invoices/{invoice}/send',    [InvoiceController::class, 'markSent'])->name('invoices.send');
    Route::patch('invoices/{invoice}/cancel',  [InvoiceController::class, 'cancel'])->name('invoices.cancel');
    Route::post('invoices/{invoice}/payment',  [InvoiceController::class, 'recordPayment'])->name('invoices.payment');
    Route::get('invoices/{invoice}/print',     [InvoiceController::class, 'printView'])->name('invoices.print');
    Route::get('invoices/{invoice}/pdf',       [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
});
