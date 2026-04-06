<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->json('credit_weights')->nullable()->after('v_o3');
        });
    }

    public function down(): void
    {
        Schema::table('financial_reports', function (Blueprint $table) {
            $table->dropColumn('credit_weights');
        });
    }
};
