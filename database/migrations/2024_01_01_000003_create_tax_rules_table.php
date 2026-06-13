<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rules', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "GST 18%", "GST 5%", "Exempt"
            $table->string('type')->default('GST'); // GST, Exempt
            // Intra-state: CGST + SGST (both half of total GST rate)
            $table->decimal('cgst_rate', 5, 2)->default(0.00);
            $table->decimal('sgst_rate', 5, 2)->default(0.00);
            // Inter-state: IGST (full GST rate)
            $table->decimal('igst_rate', 5, 2)->default(0.00);
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_rules');
    }
};
