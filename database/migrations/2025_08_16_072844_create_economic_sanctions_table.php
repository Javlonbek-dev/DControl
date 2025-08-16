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
        Schema::create('economic_sanctions', function (Blueprint $table) {
            $table->id();
            $table->integer('number');
            $table->date('registration_date');
            $table->double('assessed_fine');
            $table->foreignId('court_id')->constrained('courts');
            $table->date('decision_date');
            $table->integer('decision_number');
            $table->foreignId('decision_type_id')->constrained('decision_types');
            $table->double('imposed_fine');
            $table->boolean('is_paid');
            $table->foreignId('sanction_id')->constrained('sanction_payment_requests');
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_sanctions');
    }
};
