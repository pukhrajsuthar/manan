<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();

            // Billing address
            $table->text('billing_address');
            $table->string('billing_city');
            $table->string('billing_state');
            $table->string('billing_state_code', 2); // e.g. 24
            $table->string('billing_pincode', 10);

            // Shipping address (optional, defaults to billing)
            $table->text('shipping_address')->nullable();
            $table->string('shipping_city')->nullable();
            $table->string('shipping_state')->nullable();
            $table->string('shipping_state_code', 2)->nullable();
            $table->string('shipping_pincode', 10)->nullable();

            $table->string('gstin', 15)->nullable();
            $table->string('pan', 10)->nullable();
            $table->string('client_type')->default('individual'); // individual, business
            $table->text('notes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
