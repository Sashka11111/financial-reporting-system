<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('financial_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date');
            $table->string('company_name');
            
            // Forms 1 & 2
            $table->json('form_1_data')->nullable(); // Balance Sheet
            $table->json('form_2_data')->nullable(); // Profit and Loss
            
            // Additional Info
            $table->integer('v_n1')->nullable(); // Term of existence (years)
            $table->decimal('v_n2', 3, 1)->nullable(); // Gradation (0-5, step 0.1)
            $table->decimal('v_n3', 15, 2)->nullable(); // Sk
            $table->decimal('v_o1', 15, 2)->nullable(); // S
            $table->decimal('v_o2', 15, 2)->nullable(); // K
            $table->decimal('v_o3', 15, 2)->nullable(); // M
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('financial_reports');
    }
};
