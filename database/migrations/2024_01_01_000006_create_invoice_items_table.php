<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnDelete();
            $table->foreignId('item_id')->nullable()->constrained('items')->nullOnDelete();

            $table->string('description');
            $table->string('hsn_code', 20)->nullable();
            $table->decimal('quantity', 10, 3);
            $table->string('unit', 20)->default('Nos');
            $table->decimal('rate', 12, 2);

            // Discount per line item
            $table->decimal('discount_percent', 5, 2)->default(0.00);
            $table->decimal('discount_amount', 12, 2)->default(0.00);

            $table->decimal('taxable_amount', 12, 2); // (qty * rate) - discount_amount

            // Tax applied (copied from tax_rule at time of invoicing — immutable)
            $table->decimal('cgst_rate', 5, 2)->default(0.00);
            $table->decimal('cgst_amount', 12, 2)->default(0.00);
            $table->decimal('sgst_rate', 5, 2)->default(0.00);
            $table->decimal('sgst_amount', 12, 2)->default(0.00);
            $table->decimal('igst_rate', 5, 2)->default(0.00);
            $table->decimal('igst_amount', 12, 2)->default(0.00);

            $table->decimal('total', 12, 2); // taxable_amount + all tax amounts
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
