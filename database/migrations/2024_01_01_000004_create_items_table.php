<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('hsn_code', 20)->nullable(); // HSN code (furniture: 9403, seats: 9401)
            $table->string('unit', 20)->default('Nos'); // Nos, Sqft, Rft, Set, Pair, Kg
            $table->decimal('selling_price', 12, 2);
            $table->foreignId('tax_rule_id')->constrained('tax_rules');
            $table->string('category')->nullable(); // Sofa, Bed, Table, Chair, Wardrobe etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
