<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('show_company_gstin')->default(true);
            $table->boolean('show_client_gstin')->default(true);
        });
    }

    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['show_company_gstin', 'show_client_gstin']);
        });
    }
};
