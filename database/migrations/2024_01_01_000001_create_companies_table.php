<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('city');
            $table->string('state');
            $table->string('state_code', 2); // GST state code e.g. 24 for Gujarat
            $table->string('pincode', 10);
            $table->string('gstin', 15)->unique()->nullable(); // 15-char GST number
            $table->string('pan', 10)->nullable();
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();
            $table->string('email');
            $table->string('website')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('bank_ifsc', 11)->nullable();
            $table->string('bank_branch')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('invoice_prefix', 20)->default('MF');
            $table->unsignedInteger('invoice_counter')->default(1);
            $table->string('financial_year', 10)->default('2024-25');
            $table->string('currency', 3)->default('INR');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
