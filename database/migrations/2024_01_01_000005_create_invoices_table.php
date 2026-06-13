<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 30)->unique();
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->foreignId('company_id')->constrained('companies');
            $table->foreignId('client_id')->constrained('clients');

            // GST supply type: intra = CGST+SGST, inter = IGST
            $table->string('supply_type', 10)->default('intra');
            $table->string('place_of_supply', 2); // state code of delivery

            // Amounts (all in INR paise stored as decimal)
            $table->decimal('subtotal', 12, 2)->default(0.00);         // sum of (qty * rate)
            $table->decimal('discount_amount', 12, 2)->default(0.00);  // overall invoice discount
            $table->decimal('taxable_amount', 12, 2)->default(0.00);   // subtotal - discount
            $table->decimal('cgst_total', 12, 2)->default(0.00);
            $table->decimal('sgst_total', 12, 2)->default(0.00);
            $table->decimal('igst_total', 12, 2)->default(0.00);
            $table->decimal('total_tax', 12, 2)->default(0.00);
            $table->decimal('grand_total', 12, 2)->default(0.00);       // taxable + total_tax
            $table->decimal('round_off', 5, 2)->default(0.00);          // rounding adjustment
            $table->decimal('amount_paid', 12, 2)->default(0.00);
            $table->decimal('balance_due', 12, 2)->default(0.00);

            $table->string('payment_status', 15)->default('unpaid');    // unpaid, partial, paid
            $table->string('status', 15)->default('draft');             // draft, sent, paid, cancelled

            $table->text('notes')->nullable();       // customer-facing notes
            $table->text('terms')->nullable();       // terms & conditions

            $table->string('financial_year', 10)->default('2024-25');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
