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
        Schema::create('non_conformities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products');
            $table->foreignId('metrology_instrument_id')->constrained('metrology_instruments');
            $table->foreignId('certificate_id')->constrained('certificates');
            $table->foreignId('normative_act_id')->constrained('normative_acts');
            $table->foreignId('written_directive_id')->constrained('written_directives');
            $table->foreignId('administrative_liability_id')->constrained('administrative_liabilities');
            $table->foreignId('economic_sanction_id')->constrained('economic_sanctions');
            $table->foreignId('sanction_payment_request_id')->constrained('sanction_payment_requests');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->text('normative_documents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('non_conformities');
    }
};
