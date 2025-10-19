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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sanction_id')->nullable()->constrained('sanction_payment_requests')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('economic_sanction_id')->nullable()->constrained('economic_sanctions')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('administrative_liability_id')->nullable()->constrained('administrative_liabilities')->nullOnDelete()->cascadeOnUpdate();
            $table->date('paid_date')->nullable();
            $table->double('paid_ball')->nullable();
            $table->double('payment_amount')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
