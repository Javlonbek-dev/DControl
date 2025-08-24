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
            $table->foreignId('product_id')->nullable()->constrained('products');
            $table->foreignId('metrology_instrument_id')->nullable()->constrained('metrology_instruments');
            $table->foreignId('certificate_id')->nullable()->constrained('certificates');
            $table->foreignId('service_id')->nullable()->constrained('services');
            $table->json('normative_act_id')->nullable();
            $table->foreignId('written_directive_id')->nullable()->constrained('written_directives');
            $table->foreignId('administrative_liability_id')->nullable()->constrained('administrative_liabilities');
            $table->foreignId('economic_sanction_id')->nullable()->constrained('economic_sanctions');
            $table->foreignId('sanction_payment_request_id')->nullable()->constrained('sanction_payment_requests');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
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
