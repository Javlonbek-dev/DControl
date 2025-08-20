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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->string('ombudsman_code_number');
            $table->integer('control_days');
            $table->date('data_from');
            $table->date('data_to');
            $table->date('period_from');
            $table->date('period_to');
            $table->foreignId('company_type_id')->nullable()->constrained('company_types');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->boolean('is_district')->nullable()->default(false);
            $table->foreignId('company_id')->nullable()->constrained('companies');
            $table->timestamps();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
